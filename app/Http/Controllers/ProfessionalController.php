<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ProfessionalController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        if ($user->is_admin) {
            $professionals = \App\Models\Professional::orderBy('id', 'desc')->get();
        } else if ($user->organization_id) {
            $professionals = \App\Models\Professional::where('organization_id', $user->organization_id)->orderBy('id', 'desc')->get();
        } else {
            $professionals = collect();
        }
        return view('professionals.index', compact('professionals'));
    }

    public function create()
    {
        $user = auth()->user();
        if (!$user->is_admin && !$user->is_org_admin) {
            abort(403, 'Solo administradores pueden crear profesionales');
        }
        return view('professionals.create');
    }

    public function store(Request $request)
    {
        $user = auth()->user();
        if (!$user->is_admin && !$user->is_org_admin) {
            abort(403, 'Solo administradores pueden crear profesionales');
        }
        
        $data = $request->validate([
            'nombre_completo' => ['required', 'string', 'max:255'],
            'matricula' => ['required', 'string', 'max:255'],
            'numero_registro' => ['nullable', 'string', 'max:255'],
            'localidad' => ['required', 'string', 'max:255'],
            'provincia' => ['required', 'string', 'max:255'],
            'activo' => ['boolean'],
        ]);
        
        $data['activo'] = $request->has('activo') ? true : false;
        
        if (!$user->is_admin && $user->organization_id) {
            $data['organization_id'] = $user->organization_id;
        } else if ($user->is_admin) {
            $data['organization_id'] = $request->input('organization_id', $user->organization_id);
        }
        
        \App\Models\Professional::create($data);
        return redirect()->route('professionals.index')->with('success', 'Profesional creado exitosamente');
    }

    public function show($id)
    {
        $user = auth()->user();
        $professional = \App\Models\Professional::findOrFail($id);
        if (!$user->is_admin && $professional->organization_id != $user->organization_id) {
            abort(403);
        }
        return view('professionals.show', compact('professional'));
    }

    public function edit($id)
    {
        $user = auth()->user();
        if (!$user->is_admin && !$user->is_org_admin) {
            abort(403, 'Solo administradores pueden editar profesionales');
        }
        
        $professional = \App\Models\Professional::findOrFail($id);
        if (!$user->is_admin && $professional->organization_id != $user->organization_id) {
            abort(403);
        }
        return view('professionals.edit', compact('professional'));
    }

    public function update(Request $request, $id)
    {
        $user = auth()->user();
        if (!$user->is_admin && !$user->is_org_admin) {
            abort(403, 'Solo administradores pueden actualizar profesionales');
        }
        
        $professional = \App\Models\Professional::findOrFail($id);
        if (!$user->is_admin && $professional->organization_id != $user->organization_id) {
            abort(403);
        }
        
        $data = $request->validate([
            'nombre_completo' => ['required', 'string', 'max:255'],
            'matricula' => ['required', 'string', 'max:255'],
            'numero_registro' => ['nullable', 'string', 'max:255'],
            'localidad' => ['required', 'string', 'max:255'],
            'provincia' => ['required', 'string', 'max:255'],
            'activo' => ['boolean'],
        ]);
        
        $data['activo'] = $request->has('activo') ? true : false;
        
        $professional->update($data);
        return redirect()->route('professionals.index')->with('success', 'Profesional actualizado exitosamente');
    }

    public function destroy($id)
    {
        $user = auth()->user();
        if (!$user->is_admin && !$user->is_org_admin) {
            abort(403, 'Solo administradores pueden eliminar profesionales');
        }
        
        $professional = \App\Models\Professional::findOrFail($id);
        if (!$user->is_admin && $professional->organization_id != $user->organization_id) {
            abort(403);
        }
        $professional->delete();
        return redirect()->route('professionals.index')->with('success', 'Profesional eliminado exitosamente');
    }
}
