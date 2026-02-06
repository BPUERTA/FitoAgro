<?php

namespace App\Services;

use App\Models\Plan;
use App\Models\Organization;

class MercadoPagoService
{
    protected $mp;
    protected bool $sdkAvailable = false;

    public function __construct()
    {
        $sdkPath = base_path('vendor/mercadopago/sdk/lib/mercadopago.php');
        if (is_file($sdkPath)) {
            require_once $sdkPath;
            if (class_exists(\MP::class)) {
                $this->mp = new \MP(config('services.mercadopago.access_token'));
                $this->sdkAvailable = true;
            }
        }
    }

    /**
     * Sincronizar un plan local con Mercado Pago
     */
    public function syncPlan(Plan $plan)
    {
        $this->ensureSdk();
        // Si ya tiene un plan en MP, retornar
        if ($plan->mercadopago_plan_id) {
            return $plan->mercadopago_plan_id;
        }

        try {
            $planData = [
                "reason" => $plan->name,
                "auto_recurring" => [
                    "frequency" => 1,
                    "frequency_type" => "months",
                    "transaction_amount" => (float) $plan->monthly_price,
                    "currency_id" => $plan->currency,
                ],
                "back_url" => route('dashboard')
            ];
            if ($plan->trial_days > 0) {
                $planData["auto_recurring"]["free_trial"] = [
                    "frequency" => (int) $plan->trial_days,
                    "frequency_type" => "days"
                ];
            }
            $response = $this->mp->post('/preapproval_plan', $planData);
            if (isset($response["response"]["id"])) {
                $plan->update(['mercadopago_plan_id' => $response["response"]["id"]]);
                return $response["response"]["id"];
            }

            throw new \Exception('Error al crear plan en Mercado Pago: ' . json_encode($response));
        } catch (\Exception $e) {
            throw new \Exception('Error al crear plan en Mercado Pago: ' . $e->getMessage());
        }
    }

    /**
     * Crear suscripción para una organización
     */
    public function createSubscription(Organization $organization, Plan $plan, $payer_email, $periodicity = 'monthly')
    {
        $this->ensureSdk();
        // Asegurar que el plan esté sincronizado
        if (!$plan->mercadopago_plan_id) {
            $this->syncPlan($plan);
        }

        try {
            $subscriptionData = [
                "preapproval_plan_id" => $plan->mercadopago_plan_id,
                "reason" => "Suscripción a " . $plan->name,
                "payer_email" => $payer_email,
                "back_url" => route('subscription.success'),
                "external_reference" => (string) $organization->id,
                "status" => "pending"
            ];

            // Si la periodicidad es anual, crear una suscripción custom
            if ($periodicity === 'yearly') {
                $subscriptionData = [
                    "reason" => "Suscripción anual a " . $plan->name,
                    "auto_recurring" => [
                        "frequency" => 1,
                        "frequency_type" => "years",
                        "transaction_amount" => (float) $plan->yearly_price,
                        "currency_id" => $plan->currency,
                    ],
                    "payer_email" => $payer_email,
                    "back_url" => route('subscription.success'),
                    "external_reference" => (string) $organization->id,
                    "status" => "pending"
                ];
            }

            $response = $this->mp->create_preapproval_payment($subscriptionData);
            if (isset($response['response']['id'])) {
                $organization->update([
                    'mercadopago_subscription_id' => $response['response']['id'],
                    'mercadopago_preapproval_id' => $response['response']['id']
                ]);
                return $response['response']['init_point'] ?? null;
            }

            throw new \Exception('Error al crear suscripción en Mercado Pago: ' . json_encode($response));
        } catch (\Exception $e) {
            throw new \Exception('Error al crear suscripción en Mercado Pago: ' . $e->getMessage());
        }
    }

    /**
     * Obtener información de una suscripción
     */
    public function getSubscription($preapprovalId)
    {
        $this->ensureSdk();
        try {
            $response = $this->mp->get_preapproval_payment($preapprovalId);
            return $response;
        } catch (\Exception $e) {
            throw new \Exception('Error al obtener suscripción: ' . $e->getMessage());
        }
    }

    /**
     * Cancelar suscripción
     */
    public function cancelSubscription($preapprovalId)
    {
        $this->ensureSdk();
        try {
            $result = $this->mp->update_preapproval_payment($preapprovalId, ["status" => "cancelled"]);
            return isset($result['response']['status']) && $result['response']['status'] === 'cancelled';
        } catch (\Exception $e) {
            throw new \Exception('Error al cancelar suscripción: ' . $e->getMessage());
        }
    }

    private function ensureSdk(): void
    {
        if (!$this->sdkAvailable || !$this->mp) {
            throw new \Exception('Mercado Pago no está configurado en este servidor.');
        }
    }
}
