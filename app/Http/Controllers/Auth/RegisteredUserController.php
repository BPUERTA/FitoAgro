<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Organization;
use App\Models\Plan;
use App\Models\UserSessionLog;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(Request $request): View|RedirectResponse
    {
        if (setting('registrations_locked', '0') === '1') {
            return redirect()->route('login')->with('status', 'Los registros están temporalmente deshabilitados.');
        }

        $planId = $request->query('plan_id');
        return view('auth.register', compact('planId'));
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        if (setting('registrations_locked', '0') === '1') {
            return redirect()->route('login')->with('status', 'Los registros están temporalmente deshabilitados.');
        }

        $request->validate([
            'nombre' => ['required', 'string', 'max:255'],
            'apellido' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'organization' => ['required', 'string', 'max:255', 'unique:organizations,name'],
        ], [
            'organization.unique' => 'La organización que quiere registrar ya existe, cambie el nombre de la organización o póngase en contacto con el administrador de la misma.',
        ]);

        $organizationId = null;
        if ($request->filled('organization')) {
            $orgName = trim($request->input('organization'));
            $planId = $request->input('plan_id') ?? $request->query('plan_id');
            $selectedPlan = null;
            if ($planId) {
                $selectedPlan = Plan::where('published', true)->where('status', true)->where('id', $planId)->first();
            }
            // Si no hay plan seleccionado, usar el plan por defecto
            if (!$selectedPlan) {
                $selectedPlan = Plan::where('published', true)->where('status', true)->orderBy('monthly_price', 'asc')->first();
            }
            $trialStartDate = null;
            $trialEndDate = null;
            if ($selectedPlan && $selectedPlan->trial_days > 0) {
                $trialStartDate = now();
                $trialEndDate = now()->addDays($selectedPlan->trial_days);
            }
            $organization = Organization::create([
                'name' => $orgName,
                'status' => 'active',
                'plan_id' => $selectedPlan ? $selectedPlan->id : null,
                'trial_start_date' => $trialStartDate,
                'trial_end_date' => $trialEndDate,
            ]);
            $organizationId = $organization->id;
        }

        // Generar nickname: Primera letra del nombre + apellido en mayúsculas
        $nickname = strtoupper(substr($request->nombre, 0, 1) . $request->apellido);
        
        // Verificar si el nickname ya existe y agregar un número si es necesario
        $baseNickname = $nickname;
        $counter = 1;
        while (User::where('nickname', $nickname)->exists()) {
            $nickname = $baseNickname . $counter;
            $counter++;
        }

        $user = User::create([
            'name' => $request->nombre . ' ' . $request->apellido,
            'nombre' => $request->nombre,
            'apellido' => $request->apellido,
            'nickname' => $nickname,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'organization_id' => $organizationId,
            'is_org_admin' => $organizationId ? true : false,
        ]);

        event(new Registered($user));

        Auth::login($user);

        UserSessionLog::create([
            'user_id' => $user->id,
            'session_id' => $request->session()->getId(),
            'login_at' => now(),
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);

        return redirect()->route('billing.form');
    }
}
