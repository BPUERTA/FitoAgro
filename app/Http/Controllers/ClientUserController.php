<?php

namespace App\Http\Controllers;

use App\Models\Organization;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class ClientUserController extends Controller
{
    public function create(Organization $organization)
    {
        $organization->load('clients');
        return view('client_users.create', compact('organization'));
    }

    public function store(Request $request, Organization $organization)
    {
        $validated = $request->validate([
            'nombre' => 'required|string|max:255',
            'apellido' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
            'client_ids' => 'required|array',
            'client_ids.*' => 'exists:clients,id',
        ]);

        $user = new User();
        $user->fill($validated);
        $user->organization_id = $organization->id;
        $user->user_type = 'client';
        $user->password = Hash::make($validated['password']);
        $user->save();

        // Relación muchos a muchos con clientes
        $user->clients()->sync($validated['client_ids']);

        return redirect()->route('organizations.show', $organization->id)->with('success', 'Usuario cliente creado correctamente.');
    }

    public function crearOActualizarUsuarioCliente(Request $request)
    {
        $validated = $request->validate([
            'cuit' => 'required|string',
            'nombre' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'password' => 'nullable|string|min:8|confirmed',
        ]);

        $cliente = \App\Models\Client::where('cuit', $validated['cuit'])->first();
        if (!$cliente) {
            return response()->json(['success' => false, 'message' => 'Cliente no encontrado'], 404);
        }

        $usuario = \App\Models\User::where('cuit', $validated['cuit'])->where('user_type', 'client')->first();
        if ($usuario) {
            // Actualizar datos si cambiaron (excepto CUIT)
            $usuario->nombre = $validated['nombre'];
            $usuario->email = $validated['email'];
            if (!empty($validated['password'])) {
                $usuario->password = \Hash::make($validated['password']);
            }
            $usuario->save();
            return response()->json(['success' => true, 'message' => 'Usuario cliente actualizado', 'usuario' => $usuario]);
        } else {
            // Crear usuario cliente
            $usuario = new \App\Models\User();
            $usuario->nombre = $validated['nombre'];
            $usuario->email = $validated['email'];
            $usuario->cuit = $validated['cuit'];
            $usuario->user_type = 'client';
            $usuario->organization_id = $cliente->organization_id;
            $usuario->password = \Hash::make($validated['password']);
            $usuario->save();
            // Relacionar con el cliente
            $usuario->clients()->sync([$cliente->id]);
            return response()->json(['success' => true, 'message' => 'Usuario cliente creado', 'usuario' => $usuario]);
        }
    }
}
