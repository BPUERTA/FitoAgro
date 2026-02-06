<?php

namespace App\Http\Controllers;

use App\Models\Plan;
use Illuminate\Http\Request;

class PlanController extends Controller
{
    public function index()
    {
        if (!auth()->user()->is_admin) {
            abort(403);
        }

        $plans = Plan::orderBy('monthly_price')->get();
        return view('plans.index', compact('plans'));
    }

    public function create()
    {
        if (!auth()->user()->is_admin) {
            abort(403);
        }

        return view('plans.create');
    }

    public function store(Request $request)
    {
        if (!auth()->user()->is_admin) {
            abort(403);
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'monthly_price' => 'nullable|numeric|min:0',
            'annual_discount' => 'nullable|numeric|min:0|max:100',
            'yearly_price' => 'nullable|numeric|min:0',
            'currency' => 'required|in:ARS,USD',
            'max_users' => 'nullable|integer|min:1',
            'max_work_orders' => 'nullable|integer|min:1',
            'max_farms' => 'nullable|integer|min:1',
            'max_clients' => 'nullable|integer|min:1',
            'clients_can_create_users' => 'boolean',
            'trial_days' => 'nullable|integer|min:0',
            'status' => 'boolean',
            'published' => 'boolean',
        ]);

        $validated['status'] = $request->has('status');
        $validated['published'] = $request->has('published');
        $validated['clients_can_create_users'] = $request->has('clients_can_create_users');

        Plan::create($validated);

        return redirect()->route('plans.index')->with('success', 'Plan creado exitosamente.');
    }

    public function show(Plan $plan)
    {
        if (!auth()->user()->is_admin) {
            abort(403);
        }

        $plan->load('organizations.users', 'organizations.farms');
        return view('plans.show', compact('plan'));
    }

    public function edit(Plan $plan)
    {
        if (!auth()->user()->is_admin) {
            abort(403);
        }

        return view('plans.edit', compact('plan'));
    }

    public function update(Request $request, Plan $plan)
    {
        if (!auth()->user()->is_admin) {
            abort(403);
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'monthly_price' => 'nullable|numeric|min:0',
            'annual_discount' => 'nullable|numeric|min:0|max:100',
            'yearly_price' => 'nullable|numeric|min:0',
            'currency' => 'required|in:ARS,USD',
            'max_users' => 'nullable|integer|min:1',
            'max_work_orders' => 'nullable|integer|min:1',
            'max_farms' => 'nullable|integer|min:1',
            'max_clients' => 'nullable|integer|min:1',
            'clients_can_create_users' => 'boolean',
            'trial_days' => 'nullable|integer|min:0',
            'status' => 'boolean',
            'published' => 'boolean',
        ]);

        $validated['status'] = $request->has('status');
        $validated['published'] = $request->has('published');
        $validated['clients_can_create_users'] = $request->has('clients_can_create_users');

        $plan->update($validated);

        return redirect()->route('plans.index')->with('success', 'Plan actualizado exitosamente.');
    }

    public function destroy(Plan $plan)
    {
        if (!auth()->user()->is_admin) {
            abort(403);
        }

        $plan->delete();
        return redirect()->route('plans.index')->with('success', 'Plan eliminado exitosamente.');
    }
}
