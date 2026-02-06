<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Models\UserSessionLog;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        $request->authenticate();

        $request->session()->regenerate();

        $user = $request->user();
        if ($user) {
            UserSessionLog::create([
                'user_id' => $user->id,
                'session_id' => $request->session()->getId(),
                'login_at' => now(),
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
            ]);
        }

        return redirect()->intended(route('dashboard', absolute: false));
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $userId = $request->user()?->id;
        $sessionId = $request->session()->getId();

        Auth::guard('web')->logout();

        if ($userId && $sessionId) {
            UserSessionLog::where('user_id', $userId)
                ->where('session_id', $sessionId)
                ->whereNull('logout_at')
                ->latest('login_at')
                ->first()
                ?->update(['logout_at' => now()]);
        }

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}
