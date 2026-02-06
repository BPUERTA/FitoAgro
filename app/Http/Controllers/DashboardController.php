<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\Farm;
use App\Models\Organization;
use Illuminate\Support\Facades\Schema;
use App\Models\RegistroTecnico;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $user = auth()->user();
        
        // Si el usuario tiene organización asignada, mostrar sus datos
        if ($user->organization_id) {
            $organizationId = $user->organization_id;
        } else {
            // Si es super admin, mostrar totales generales
            $organizationId = null;
        }

        $isSuperAdmin = $user->is_admin ?? false;
        $hasRegistroTable = Schema::hasTable('registro_tecnicos');

        $stats = [
            'systemUsers' => $organizationId
                ? \App\Models\User::where('organization_id', $organizationId)->where('user_type', 'organization')->count()
                : \App\Models\User::where('user_type', 'organization')->count(),
            'clientUsers' => $organizationId
                ? \App\Models\User::where('organization_id', $organizationId)->where('user_type', 'client')->count()
                : \App\Models\User::where('user_type', 'client')->count(),
            'clients' => $organizationId 
                ? Client::where('organization_id', $organizationId)->count()
                : Client::count(),
            'farms' => $organizationId
                ? Farm::where('organization_id', $organizationId)->count()
                : Farm::count(),
            'organizations' => $isSuperAdmin ? Organization::count() : 1,
            'registroTecnicos' => $hasRegistroTable
                ? ($organizationId
                    ? RegistroTecnico::where('organization_id', $organizationId)->count()
                    : RegistroTecnico::count())
                : 0,
            'workOrders' => $organizationId 
                ? \App\Models\WorkOrder::where('organization_id', $organizationId)->count()
                : \App\Models\WorkOrder::count(),
            'applicationRecipes' => 0, // Pendiente de crear tabla
            'saleRecipes' => 0, // Pendiente de crear tabla
        ];

        return view('dashboard', compact('stats'));
    }
}
