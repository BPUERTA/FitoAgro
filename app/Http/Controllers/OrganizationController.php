<?php

namespace App\Http\Controllers;

use App\Models\Organization;
use App\Models\Plan;
use Illuminate\Http\Request;

class OrganizationController extends Controller
{
    public function index()
    {
        $organizations = Organization::with('plan')->orderBy('id', 'desc')->get();
        return view('organizations.index', compact('organizations'));
    }

    public function create()
    {
        $plans = Plan::where('published', true)->orderBy('monthly_price')->get();
        return view('organizations.create', compact('plans'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'plan_id' => ['required', 'exists:plans,id'],
            'status' => ['required', 'in:active,suspended,inactive'],
        ]);

        Organization::create($data);

        return redirect()
            ->route('organizations.index')
            ->with('success', 'Organización creada.');
    }

    public function show(Organization $organization)
    {
        // Permitir a superadmin ver cualquier organización
        // O permitir a usuarios ver solo su propia organización
        if (!auth()->user()->is_admin && auth()->user()->organization_id !== $organization->id) {
            abort(403, 'No tienes permiso para ver esta organización.');
        }

        $organization->load('payments');
        return view('organizations.show', compact('organization'));
    }

    public function edit(Organization $organization)
    {
        // Mostrar todos los planes a superadmin, a otros usuarios solo los publicados
        if (auth()->user()->is_admin) {
            $plans = Plan::orderBy('monthly_price')->get();
        } else {
            $plans = Plan::where('published', true)->orderBy('monthly_price')->get();
        }

        return view('organizations.edit', compact('organization', 'plans'));
    }

    public function update(Request $request, Organization $organization)
    {
        $rules = [
            'name' => ['required', 'string', 'max:255'],
            'plan_id' => ['required', 'exists:plans,id'],
            'status' => ['required', 'in:active,suspended,inactive'],
        ];

        // Solo superadmin puede editar las fechas
        if (auth()->user()->is_admin) {
            $rules['trial_start_date'] = ['nullable', 'date'];
            $rules['trial_end_date'] = ['nullable', 'date', 'after_or_equal:trial_start_date'];
            $rules['paid_plan_start_date'] = ['nullable', 'date'];
            $rules['paid_plan_end_date'] = ['nullable', 'date', 'after_or_equal:paid_plan_start_date'];
        }

        $data = $request->validate($rules);

        $organization->update($data);

        return redirect()
            ->route('organizations.index')
            ->with('success', 'Organización actualizada.');
    }

    public function destroy(Organization $organization)
    {
        $organization->delete();

        return redirect()
            ->route('organizations.index')
            ->with('success', 'Organización eliminada.');
    }
}
