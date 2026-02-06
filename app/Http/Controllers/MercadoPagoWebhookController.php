<?php

namespace App\Http\Controllers;

use App\Models\Organization;
use App\Models\Payment;
use App\Services\MercadoPagoService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use MercadoPago\SDK;

class MercadoPagoWebhookController extends Controller
{
    protected $mpService;

    public function __construct(MercadoPagoService $mpService)
    {
        $this->mpService = $mpService;
    }

    protected function isValidSignature(Request $request): bool
    {
        $secret = config('services.mercadopago.webhook_secret');
        if (!$secret) {
            return true;
        }

        $signatureHeader = $request->header('x-signature')
            ?? $request->header('x-mercadopago-signature');

        if (!$signatureHeader) {
            return false;
        }

        $timestamp = null;
        $providedHash = null;

        foreach (explode(',', $signatureHeader) as $part) {
            $pair = array_map('trim', explode('=', $part, 2));
            if (count($pair) === 2) {
                if ($pair[0] === 'ts') {
                    $timestamp = $pair[1];
                }
                if ($pair[0] === 'v1') {
                    $providedHash = $pair[1];
                }
            }
        }

        if (!$providedHash) {
            $providedHash = $signatureHeader;
        }

        $payload = $request->getContent();
        $computed = hash_hmac('sha256', $payload, $secret);
        if (hash_equals($computed, $providedHash)) {
            return true;
        }

        if ($timestamp !== null) {
            $computedWithTs = hash_hmac('sha256', $timestamp . '.' . $payload, $secret);
            if (hash_equals($computedWithTs, $providedHash)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Recibir notificaciones de Mercado Pago
     */
    public function handle(Request $request)
    {
        if (!$this->isValidSignature($request)) {
            Log::warning('Webhook de Mercado Pago con firma invalida.');
            return response()->json(['status' => 'error', 'message' => 'Invalid signature'], 401);
        }

        try {
            // Log de la notificación recibida
            Log::info('Webhook recibido de Mercado Pago', $request->all());

            $type = $request->input('type');
            $data = $request->input('data');

            // Procesar según el tipo de notificación
            switch ($type) {
                case 'subscription_preapproval':
                    $this->handleSubscription($data['id']);
                    break;

                case 'subscription_authorized_payment':
                    $this->handlePayment($data['id']);
                    break;

                case 'subscription_preapproval_updated':
                    $this->handleSubscriptionUpdate($data['id']);
                    break;

                default:
                    Log::info('Tipo de webhook no manejado: ' . $type);
            }

            return response()->json(['status' => 'success'], 200);

        } catch (\Exception $e) {
            Log::error('Error procesando webhook de Mercado Pago: ' . $e->getMessage());
            return response()->json(['status' => 'error', 'message' => $e->getMessage()], 500);
        }
    }

    /**
     * Manejar suscripción aprobada
     */
    protected function handleSubscription($preapprovalId)
    {
        $subscription = $this->mpService->getSubscription($preapprovalId);
        
        if ($subscription && $subscription->status === 'authorized') {
            $organization = Organization::where('mercadopago_subscription_id', $preapprovalId)->first();

            if ($organization) {
                // Activar plan pago
                $organization->update([
                    'status' => 'active',
                    'paid_plan_start_date' => now(),
                    'paid_plan_end_date' => now()->addMonth(),
                ]);

                Log::info("Suscripción activada para organización: {$organization->id}");
            }
        }
    }

    /**
     * Manejar pago autorizado
     */
    protected function handlePayment($paymentId)
    {
        SDK::setAccessToken(config('services.mercadopago.access_token'));

        $payment = \MercadoPago\Payment::find_by_id($paymentId);

        if ($payment && $payment->status === 'approved') {
            $preapprovalId = $payment->metadata->preapproval_id ?? null;
            $organization = null;

            if ($preapprovalId) {
                $organization = Organization::where('mercadopago_subscription_id', $preapprovalId)->first();
            }

            if (!$organization && !empty($payment->external_reference)) {
                $organization = Organization::find($payment->external_reference);
            }

            if ($organization) {
                $note = "Pago de suscripcion - ID: {$paymentId}";
                if (Payment::where('notes', $note)->exists()) {
                    Log::info("Pago duplicado ignorado para organizacion: {$organization->id}");
                    return;
                }

                // Registrar pago
                Payment::create([
                    'organization_id' => $organization->id,
                    'amount' => $payment->transaction_amount,
                    'currency' => $payment->currency_id,
                    'payment_date' => now(),
                    'status' => 'completed',
                    'payment_method' => $payment->payment_method_id,
                    'notes' => $note,
                ]);

                // Extender periodo de pago
                if ($organization->paid_plan_end_date) {
                    $organization->update([
                        'paid_plan_end_date' => $organization->paid_plan_end_date->addMonth(),
                    ]);
                }

                Log::info("Pago registrado para organizacion: {$organization->id}");
            }
        }
    }
    /**
     * Manejar actualización de suscripción
     */
    protected function handleSubscriptionUpdate($preapprovalId)
    {
        $subscription = $this->mpService->getSubscription($preapprovalId);
        $organization = Organization::where('mercadopago_subscription_id', $preapprovalId)->first();

        if ($organization && $subscription) {
            // Si la suscripción fue cancelada o pausada
            if (in_array($subscription->status, ['cancelled', 'paused'])) {
                $organization->update(['status' => 'suspended']);
                Log::info("Suscripción suspendida para organización: {$organization->id}");
            }
        }
    }
}
