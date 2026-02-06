<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class TeamController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        if ($user->is_admin) {
            $teams = \App\Models\Team::orderBy('id', 'desc')->get();
        } else if ($user->organization_id) {
            $teams = \App\Models\Team::where('organization_id', $user->organization_id)->orderBy('id', 'desc')->get();
        } else {
            $teams = collect();
        }
        return view('teams.index', compact('teams'));
    }

    public function create()
    {
        return view('teams.create');
    }

    public function store(Request $request)
    {
        $user = auth()->user();
        
        $rules = [
            'name' => ['required', 'string', 'max:255'],
            'tipo_equipo' => ['required', 'in:fumigacion,fertilizacion_liquida,fertilizacion_solida,siembra,cosecha,laboreo'],
            'tipo_propiedad' => ['required', 'in:propio,contratista'],
            'activo' => ['nullable', 'boolean'],
        ];
        
        if ($request->input('tipo_propiedad') === 'contratista') {
            $rules['contratistas'] = ['required', 'string'];
        }
        
        $data = $request->validate($rules);
        
        if (!$user->is_admin && $user->organization_id) {
            $data['organization_id'] = $user->organization_id;
        } else if ($user->is_admin) {
            $data['organization_id'] = $request->input('organization_id');
        }
        
        $data['activo'] = $request->has('activo') ? true : false;
        
        if ($request->input('tipo_propiedad') !== 'contratista') {
            $data['contratistas'] = null;
        }
        
        \App\Models\Team::create($data);
        return redirect()->route('teams.index')->with('success', 'Equipo creado exitosamente');
    }

    public function show($id)
    {
        $user = auth()->user();
        $team = \App\Models\Team::findOrFail($id);
        if (!$user->is_admin && $team->organization_id != $user->organization_id) {
            abort(403);
        }
        return view('teams.show', compact('team'));
    }

    public function edit($id)
    {
        $user = auth()->user();
        $team = \App\Models\Team::findOrFail($id);
        if (!$user->is_admin && $team->organization_id != $user->organization_id) {
            abort(403);
        }
        return view('teams.edit', compact('team'));
    }

    public function update(Request $request, $id)
    {
        $user = auth()->user();
        $team = \App\Models\Team::findOrFail($id);
        if (!$user->is_admin && $team->organization_id != $user->organization_id) {
            abort(403);
        }
        
        $rules = [
            'name' => ['required', 'string', 'max:255'],
            'tipo_equipo' => ['required', 'in:fumigacion,fertilizacion_liquida,fertilizacion_solida,siembra,cosecha,laboreo'],
            'tipo_propiedad' => ['required', 'in:propio,contratista'],
            'activo' => ['nullable', 'boolean'],
        ];
        
        if ($request->input('tipo_propiedad') === 'contratista') {
            $rules['contratistas'] = ['required', 'string'];
        }
        
        $data = $request->validate($rules);
        
        $data['activo'] = $request->has('activo') ? true : false;
        
        if ($request->input('tipo_propiedad') !== 'contratista') {
            $data['contratistas'] = null;
        }
        
        $team->update($data);
        return redirect()->route('teams.index')->with('success', 'Equipo actualizado exitosamente');
    }

    public function destroy($id)
    {
        $user = auth()->user();
        $team = \App\Models\Team::findOrFail($id);
        if (!$user->is_admin && $team->organization_id != $user->organization_id) {
            abort(403);
        }
        $team->delete();
        return redirect()->route('teams.index')->with('success', 'Equipo eliminado');
    }
}
