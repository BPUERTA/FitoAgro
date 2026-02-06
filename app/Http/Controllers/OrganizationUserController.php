<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OrganizationUserController extends Controller
{
    public function index()
    {
        $orgId = Auth::user()->organization_id;
        // Usuarios de organización (no asociados a cliente)
        $organizationUsers = User::where('organization_id', $orgId)
            ->where(function($q) {
                $q->whereNull('user_type')->orWhere('user_type', 'organization');
            })
            ->get();
        // Usuarios de clientes (asociados a cliente)
        $clientUsers = User::where('organization_id', $orgId)
            ->where('user_type', 'client')
            ->get();
        return view('organization_users.index', compact('organizationUsers', 'clientUsers'));
    }
}
