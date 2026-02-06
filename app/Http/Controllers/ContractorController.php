<?php

namespace App\Http\Controllers;

use App\Models\Contractor;
use Illuminate\Http\Request;

class ContractorController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        if ($user->is_admin) {
            $contractors = Contractor::with('teams')->orderBy('id', 'desc')->get();
        } else if ($user->organization_id) {
            $contractors = Contractor::with('teams')->where('organization_id', $user->organization_id)->orderBy('id', 'desc')->get();
        } else {
            $contractors = collect();
        }
        return view('contractors.index', compact('contractors'));
    }

    public function create()
    {
        return view('contractors.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nombre' => 'required|string|max:255',
            'cuit' => 'required|string|max:20|unique:contractors',
            'activo' => 'nullable|boolean',
        ]);

        $validated['organization_id'] = auth()->user()->organization_id;
        $validated['activo'] = $request->has('activo');
        $validated['nombre'] = ucwords(strtolower($validated['nombre']));

        // Calcular el siguiente número para esta organización
        $lastContractor = Contractor::where('organization_id', $validated['organization_id'])
            ->orderBy('numero', 'desc')
            ->first();
        
        $validated['numero'] = $lastContractor ? $lastContractor->numero + 1 : 1;

        Contractor::create($validated);

        return redirect()->route('contractors.index')->with('success', 'Contratista creado exitosamente.');
    }

    public function show(Contractor $contractor)
    {
        $user = auth()->user();
        if (!$user->is_admin && $contractor->organization_id != $user->organization_id) {
            abort(403);
        }
        $contractor->load('teams');
        return view('contractors.show', compact('contractor'));
    }

    public function edit(Contractor $contractor)
    {
        $user = auth()->user();
        if (!$user->is_admin && $contractor->organization_id != $user->organization_id) {
            abort(403);
        }
        return view('contractors.edit', compact('contractor'));
    }

    public function update(Request $request, Contractor $contractor)
    {
        $user = auth()->user();
        if (!$user->is_admin && $contractor->organization_id != $user->organization_id) {
            abort(403);
        }

        $validated = $request->validate([
            'nombre' => 'required|string|max:255',
            'cuit' => 'required|string|max:20|unique:contractors,cuit,' . $contractor->id,
            'activo' => 'nullable|boolean',
        ]);

        $validated['activo'] = $request->has('activo');
        $validated['nombre'] = ucwords(strtolower($validated['nombre']));

        $contractor->update($validated);

        return redirect()->route('contractors.index')->with('success', 'Contratista actualizado exitosamente.');
    }

    public function destroy(Contractor $contractor)
    {
        $user = auth()->user();
        if (!$user->is_admin && $contractor->organization_id != $user->organization_id) {
            abort(403);
        }

        $contractor->delete();
        return redirect()->route('contractors.index')->with('success', 'Contratista eliminado exitosamente.');
    }
}
