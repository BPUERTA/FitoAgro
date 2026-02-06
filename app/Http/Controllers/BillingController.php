<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Organization;

class BillingController extends Controller
{
    public function showForm()
    {
        $user = Auth::user();
        $organization = $user->organization;
        $plans = \App\Models\Plan::where('published', true)->where('status', true)->orderBy('monthly_price')->get();
        $ivaOptions = [
            'Responsable Inscripto',
            'Monotributista',
            'Exento',
            'Consumidor Final',
            'No Responsable',
        ];
        return view('billing.form', compact('user', 'organization', 'plans', 'ivaOptions'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'billing_name' => 'required|string|max:255',
            'billing_address' => 'required|string|max:255',
            'billing_cuit' => 'required|string|max:20',
            'billing_iva' => 'required|string|max:50',
            'plan_id' => 'required|exists:plans,id',
        ]);

        $user = Auth::user();
        $organization = $user->organization;
        $organization->update([
            'billing_name' => $request->billing_name,
            'billing_address' => $request->billing_address,
            'billing_cuit' => $request->billing_cuit,
            'billing_iva' => $request->billing_iva,
            'plan_id' => $request->plan_id,
        ]);

        // Redirigir a la pantalla de periodicidad antes del pago
        return redirect()->route('subscription.periodicity', ['plan' => $request->plan_id]);
    }
}
