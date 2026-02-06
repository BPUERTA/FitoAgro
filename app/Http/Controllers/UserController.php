<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Organization;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        if ($user->is_admin) {
            $users = User::with('organization')->orderBy('id', 'desc')->get();
        } else if ($user->organization_id) {
            $users = User::with('organization')
                ->where('organization_id', $user->organization_id)
                ->orderBy('id', 'desc')
                ->get();
        } else {
            $users = collect();
        }
        return view('users.index', compact('users'));
    }

    public function create()
    {
        $user = auth()->user();
        if (!$user->is_admin && !$user->is_org_admin) {
            abort(403);
        }
        // Limitar usuarios segun el plan de la organizacion
        if (!$user->is_admin && $user->organization_id) {
            $count = User::where('organization_id', $user->organization_id)->count();
            $plan = $user->organization?->plan;
            $maxUsers = $plan?->max_users;
            if ($maxUsers && $count >= $maxUsers) {
                $planName = $plan?->name ?? 'del plan actual';
                return redirect()->route('users.index')
                    ->with('error', "Solo se permiten {$maxUsers} usuarios para el plan {$planName}.");
            }
        }
        return view('users.create');
    }

    public function store(Request $request)
    {
        $user = auth()->user();
        if (!$user->is_admin && !$user->is_org_admin) {
            abort(403);
        }
        // Limitar usuarios segun el plan de la organizacion
        if (!$user->is_admin && $user->organization_id) {
            $count = User::where('organization_id', $user->organization_id)->count();
            $plan = $user->organization?->plan;
            $maxUsers = $plan?->max_users;
            if ($maxUsers && $count >= $maxUsers) {
                $planName = $plan?->name ?? 'del plan actual';
                return redirect()->route('users.index')
                    ->with('error', "Solo se permiten {$maxUsers} usuarios para el plan {$planName}.");
            }
        }
        $data = $request->validate([
            'nombre' => ['required', 'string', 'max:255'],
            'apellido' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);
        
        // Generar nickname: Primera letra del nombre + apellido en may?sculas
        $nickname = strtoupper(substr($data['nombre'], 0, 1) . $data['apellido']);
        
        // Verificar si el nickname ya existe y agregar un n?mero si es necesario
        $baseNickname = $nickname;
        $counter = 1;
        while (User::where('nickname', $nickname)->exists()) {
            $nickname = $baseNickname . $counter;
            $counter++;
        }
        
        $data['nickname'] = $nickname;
        $data['name'] = $data['nombre'] . ' ' . $data['apellido']; // Mantener compatibilidad
        $data['password'] = bcrypt($data['password']);
        if (!$user->is_admin) {
            $data['organization_id'] = $user->organization_id;
        }
        User::create($data);
        return redirect()->route('users.index')->with('success', 'Usuario creado.');
    }

    public function edit(User $user)
    {
        $auth = auth()->user();
        if (!$auth->is_admin && (!$auth->is_org_admin || $user->organization_id != $auth->organization_id)) {
            abort(403);
        }
        
        // Contar cu?ntos org_admin hay en la organizaci?n
        $orgAdminCount = 0;
        if ($user->organization_id) {
            $orgAdminCount = User::where('organization_id', $user->organization_id)
                                  ->where('is_org_admin', true)
                                  ->count();
        }
        
        $isOnlyOrgAdmin = $user->is_org_admin && $orgAdminCount === 1;
        
        // Cargar organizaciones si es super admin
        $organizations = [];
        if ($auth->is_admin) {
            $organizations = Organization::orderBy('name')->get();
        }
        
        return view('users.edit', compact('user', 'isOnlyOrgAdmin', 'organizations'));
    }

    public function update(Request $request, User $user)
    {
        $auth = auth()->user();
        if (!$auth->is_admin && (!$auth->is_org_admin || $user->organization_id != $auth->organization_id)) {
            abort(403);
        }
        
        $rules = [
            'nombre' => ['required', 'string', 'max:255'],
            'apellido' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email,' . $user->id],
        ];
        
        // Solo super admin puede modificar el nickname y organizaci?n
        if ($auth->is_admin) {
            $rules['nickname'] = ['required', 'string', 'max:255', 'unique:users,nickname,' . $user->id];
        }
        
        $data = $request->validate($rules);
        \Log::info('User update request', [
            'auth_user_id' => $auth->id,
            'editing_user_id' => $user->id,
            'input' => $request->all(),
            'validated' => $data,
            'before_update' => $user->toArray(),
        ]);
        
        // Manejar organization_id por separado para permitir valores vac?os
        if ($auth->is_admin) {
            $orgId = $request->input('organization_id');
            if ($orgId === '' || $orgId === null) {
                $data['organization_id'] = null;
            } else {
                // Validar que la organizaci?n existe
                if (!Organization::where('id', $orgId)->exists()) {
                    return redirect()->back()->withErrors(['organization_id' => 'La organizaci?n seleccionada no es v?lida.'])->withInput();
                }
                $data['organization_id'] = $orgId;
            }
        }
        
        // Si NO es super admin, regenerar nickname autom?ticamente si cambiaron nombre o apellido
        if (!$auth->is_admin && ($data['nombre'] !== $user->nombre || $data['apellido'] !== $user->apellido)) {
            $nickname = strtoupper(substr($data['nombre'], 0, 1) . $data['apellido']);
            
            // Verificar si el nickname ya existe (excluyendo el usuario actual)
            $baseNickname = $nickname;
            $counter = 1;
            while (User::where('nickname', $nickname)->where('id', '!=', $user->id)->exists()) {
                $nickname = $baseNickname . $counter;
                $counter++;
            }
            
            $data['nickname'] = $nickname;
        }
        
        // Sincronizar siempre los campos 'name', 'nombre' y 'apellido'
        // Forzar sincronizaci?n de nombre y apellido aunque no est?n en $data por validaci?n
        // Forzar siempre la actualizaci?n de nombre y apellido
        $data['nombre'] = $request->input('nombre');
        $data['apellido'] = $request->input('apellido');
        $data['name'] = $data['nombre'] . ' ' . $data['apellido'];
        
        // Manejo de permisos
        // Solo super admin puede otorgar permisos de super admin
        if ($auth->is_admin) {
            $isLastSuperAdmin = $user->is_admin && User::where('is_admin', true)->count() === 1;
            if ($isLastSuperAdmin && !$request->has('is_admin')) {
                return redirect()->route('users.edit', $user)
                    ->with('error', 'No se puede quitar el permiso de super admin al único super admin del sistema.');
            }
            $data['is_admin'] = $request->has('is_admin') ? true : false;
        }
        
        // Tanto admin como org_admin pueden otorgar permisos de org_admin
        if ($auth->is_admin || $auth->is_org_admin) {
            $newIsOrgAdmin = $request->has('is_org_admin') ? true : false;
            
            if ($user->is_org_admin && $user->organization_id) {
                $orgAdminCount = User::where('organization_id', $user->organization_id)
                                      ->where('is_org_admin', true)
                                      ->count();
                
                // No permitir quitar el permiso si es el unico org_admin
                if ($orgAdminCount === 1) {
                    $newIsOrgAdmin = true;
                } elseif (!$newIsOrgAdmin) {
                    // Buscar el siguiente usuario de la organizacion para asignarle admin
                    $nextUser = User::where('organization_id', $user->organization_id)
                                    ->where('id', '!=', $user->id)
                                    ->first();
                    
                    if ($nextUser) {
                        $nextUser->is_org_admin = true;
                        $nextUser->save();
                    } else {
                        // Si no hay otro usuario, no permitir quitar el permiso
                        return redirect()->route('users.edit', $user)
                                       ->with('error', 'No se puede quitar el permiso de administrador porque es el unico usuario de la organizacion.');
                    }
                }
            }
            
            $data['is_org_admin'] = $newIsOrgAdmin;
        }
        
        // Forzar actualizaci?n directa de nombre y apellido
        $user->nombre = $request->input('nombre');
        $user->apellido = $request->input('apellido');
        $user->name = $user->nombre . ' ' . $user->apellido;
        $user->update($data);
        \Log::info('User after update', [
            'user_id' => $user->id,
            'after_update' => $user->fresh()->toArray(),
        ]);
        return redirect()->route('users.index')->with('success', 'Usuario actualizado.');
    }

    public function destroy(User $user)
    {
        $auth = auth()->user();
        if (!$auth->is_admin && (!$auth->is_org_admin || $user->organization_id != $auth->organization_id)) {
            abort(403);
        }

        if ($user->is_admin && User::where('is_admin', true)->count() === 1) {
            return redirect()->route('users.index')
                ->with('error', 'No se puede eliminar al único super admin del sistema.');
        }
        
        // Si el usuario es org_admin y hay una organizaci?n
        if ($user->is_org_admin && $user->organization_id) {
            // Contar cu?ntos org_admin hay en la organizaci?n
            $orgAdminCount = User::where('organization_id', $user->organization_id)
                                  ->where('is_org_admin', true)
                                  ->count();
            
            // Si es el unico org_admin, asignar permiso al siguiente usuario
            if ($orgAdminCount === 1) {
                $nextUser = User::where('organization_id', $user->organization_id)
                                ->where('id', '!=', $user->id)
                                ->first();
                
                if ($nextUser) {
                    $nextUser->is_org_admin = true;
                    $nextUser->save();
                }
            }
        }
        
        $user->delete();
        return redirect()->route('users.index')->with('success', 'Usuario eliminado.');
    }

    public function show(User $user, Request $request)
    {
        $auth = auth()->user();
        if (!$auth->is_admin && $user->organization_id != $auth->organization_id) {
            abort(403);
        }
        
        // Si es una petici?n AJAX, devolver JSON
        if ($request->wantsJson() || $request->ajax()) {
            return response()->json([
                'id' => $user->id,
                'nombre' => $user->nombre,
                'apellido' => $user->apellido,
                'nickname' => $user->nickname,
                'email' => $user->email,
                'telefono' => $user->telefono,
                'direccion' => $user->direccion,
                'is_org_admin' => $user->is_org_admin,
                'is_admin' => $user->is_admin,
                'organization' => $user->organization ? [
                    'id' => $user->organization->id,
                    'name' => $user->organization->name
                ] : null,
                'created_at' => $user->created_at->format('d/m/Y H:i'),
                'updated_at' => $user->updated_at->format('d/m/Y H:i')
            ]);
        }
        
        return view('users.show', compact('user'));
    }
}
