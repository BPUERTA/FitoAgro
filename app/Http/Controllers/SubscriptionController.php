<?php

namespace App\Http\Controllers;

use App\Models\Plan;
use App\Services\MercadoPagoService;
use Illuminate\Http\Request;

class SubscriptionController extends Controller
{
    protected $mpService;

    public function __construct(MercadoPagoService $mpService)
    {
        $this->mpService = $mpService;
    }

    /**
     * Pantalla para elegir periodicidad antes de suscribirse
     */
    public function selectPeriodicity(Plan $plan)
    {
        return view('subscription.periodicity', compact('plan'));
    }

    /**
     * Mostrar planes disponibles
     */
    public function index()
    {
        $plans = Plan::where('published', true)
            ->where('status', true)
            ->orderBy('monthly_price')
            ->get();

        $currentOrganization = auth()->user()->organization;

        return view('subscription.index', compact('plans', 'currentOrganization'));
    }

    /**
     * Iniciar proceso de suscripción
     */
    public function subscribe(Request $request, Plan $plan)
    {
        $organization = auth()->user()->organization;

        if (!$organization) {
            return redirect()->back()->with('error', 'No tienes una organización asociada.');
        }

        $periodicity = $request->input('periodicity', 'monthly');
        try {
            // Crear suscripción en Mercado Pago
            $initPoint = $this->mpService->createSubscription(
                $organization,
                $plan,
                auth()->user()->email,
                $periodicity
            );
            // Redirigir a Mercado Pago
            return redirect($initPoint);
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Error al procesar la suscripción: ' . $e->getMessage());
        }
    }

    /**
     * Callback después de suscripción exitosa
     */
    public function success(Request $request)
    {
        return view('subscription.success');
    }

    /**
     * Cancelar suscripción
     */
    public function cancel()
    {
        $organization = auth()->user()->organization;

        if (!$organization || !$organization->mercadopago_subscription_id) {
            return redirect()->back()->with('error', 'No tienes una suscripción activa.');
        }

        try {
            $this->mpService->cancelSubscription($organization->mercadopago_subscription_id);
            
            $organization->update(['status' => 'suspended']);

            return redirect()->route('subscription.index')->with('success', 'Suscripción cancelada correctamente.');

        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Error al cancelar la suscripción: ' . $e->getMessage());
        }
    }
}
