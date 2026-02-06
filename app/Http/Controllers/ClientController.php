<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\Organization;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ClientController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        if ($user->is_admin) {
            $clients = Client::orderBy('id', 'desc')->get();
        } else if ($user->organization_id) {
            $clients = Client::where('organization_id', $user->organization_id)->orderBy('id', 'desc')->get();
        } else {
            $clients = collect();
        }
        return view('clients.index', compact('clients'));
    }

    public function create()
    {
        $organizations = Organization::all();
        return view('clients.create', compact('organizations'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'domicilio' => ['nullable', 'string', 'max:255'],
            'altura' => ['nullable', 'string', 'max:50'],
            'localidad' => ['nullable', 'string', 'max:255'],
            'provincia' => ['nullable', 'string', 'max:255'],
            'pais' => ['nullable', 'string', 'max:255'],
            'status' => ['required', 'in:active,inactive'],
            'organization_id' => ['nullable', 'exists:organizations,id'],
            'cuit' => ['nullable', 'string', 'max:20', 'unique:clients,cuit'],
            'email' => ['nullable', 'email', 'max:255', 'unique:clients,email'],
        ], [
            'cuit.unique' => 'El CUIT ya existe.',
            'email.unique' => 'El email ya existe.',
        ]);

        \Log::info('Datos recibidos en store:', $data);

        // Si el usuario tiene organización asignada y no es super admin, usar la suya
        if (auth()->user()->organization_id) {
            $data['organization_id'] = auth()->user()->organization_id;
        }

        Client::create($data);

        return redirect()
            ->route('clients.index')
            ->with('success', 'Cliente creado.');
    }

    public function show(Client $client)
    {
        $user = auth()->user();
        if (!$user->is_admin && $client->organization_id != $user->organization_id) {
            abort(403);
        }

        $farms = $client->farms()->orderBy('name')->get();
        return view('clients.show', compact('client', 'farms'));
    }

    public function edit(Client $client)
    {
        $organizations = Organization::all();
        return view('clients.edit', compact('client', 'organizations'));
    }

    public function update(Request $request, Client $client)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'domicilio' => ['nullable', 'string', 'max:255'],
            'altura' => ['nullable', 'string', 'max:50'],
            'localidad' => ['nullable', 'string', 'max:255'],
            'provincia' => ['nullable', 'string', 'max:255'],
            'pais' => ['nullable', 'string', 'max:255'],
            'status' => ['required', 'in:active,inactive'],
            'organization_id' => ['nullable', 'exists:organizations,id'],
            'cuit' => ['nullable', 'string', 'max:20', Rule::unique('clients', 'cuit')->ignore($client->id)],
            'email' => ['nullable', 'email', 'max:255', Rule::unique('clients', 'email')->ignore($client->id)],
        ], [
            'cuit.unique' => 'El CUIT ya existe.',
            'email.unique' => 'El email ya existe.',
        ]);

        \Log::info('Datos recibidos en update:', $data);

        // Si el usuario tiene organización asignada y no es super admin, usar la suya
        if (auth()->user()->organization_id) {
            $data['organization_id'] = auth()->user()->organization_id;
        }

        $client->update($data);

        return redirect()
            ->route('clients.index')
            ->with('success', 'Cliente actualizado.');
    }

    public function destroy(Client $client)
    {
        $client->delete();

        return redirect()
            ->route('clients.index')
            ->with('success', 'Cliente eliminado.');
    }

    public function buscarPorCuit(Request $request)
    {
        $cuit = $request->query('cuit');
        \Log::info('BuscarPorCuit - CUIT recibido:', ['cuit' => $cuit]);
        $cliente = \App\Models\Client::where('cuit', $cuit)->first();
        if (!$cliente) {
            return response()->json(['success' => false, 'message' => 'Cliente no encontrado']);
        }
        $usuario = \App\Models\User::where('cuit', $cuit)->where('user_type', 'client')->first();
        return response()->json([
            'success' => true,
            'cliente' => [
                'id' => $cliente->id,
                'name' => $cliente->name,
                'email' => $cliente->email,
                'cuit' => $cliente->cuit,
            ],
            'usuario' => $usuario ? [
                'id' => $usuario->id,
                'nombre' => $usuario->nombre,
                'email' => $usuario->email,
                'cuit' => $usuario->cuit,
            ] : null
        ]);
    }
}
