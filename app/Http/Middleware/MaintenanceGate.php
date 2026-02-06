<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class MaintenanceGate
{
    public function handle(Request $request, Closure $next): Response
    {
        $enabled = setting('maintenance_enabled', '0') === '1';
        $startAt = setting('maintenance_start_at');
        $endAt = setting('maintenance_end_at');

        $scheduledActive = false;
        if ($startAt || $endAt) {
            try {
                $now = now();
                $start = $startAt ? \Illuminate\Support\Carbon::parse($startAt) : null;
                $end = $endAt ? \Illuminate\Support\Carbon::parse($endAt) : null;

                if ($start && $end) {
                    $scheduledActive = $now->betweenIncluded($start, $end);
                } elseif ($start && !$end) {
                    $scheduledActive = $now->gte($start);
                } elseif (!$start && $end) {
                    $scheduledActive = $now->lte($end);
                }
            } catch (\Throwable $e) {
                $scheduledActive = false;
            }
        }

        if (!$enabled && !$scheduledActive) {
            return $next($request);
        }

        $user = $request->user();
        $isAdmin = $user && $user->is_admin;

        if ($isAdmin) {
            return $next($request);
        }

        if ($request->is('login')
            || $request->is('register')
            || $request->is('forgot-password')
            || $request->is('reset-password/*')
            || $request->is('password/*')
            || ($request->route() && in_array($request->route()->getName(), [
                'login',
                'password.request',
                'password.email',
                'password.reset',
                'password.store',
                'verification.notice',
            ], true))
        ) {
            return $next($request);
        }

        return response()->view('maintenance', [
            'message' => setting('maintenance_message', 'Estamos trabajando en el sitio. Volvé más tarde.'),
        ], 503);
    }
}
