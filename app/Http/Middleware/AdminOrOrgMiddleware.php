<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class AdminOrOrgMiddleware
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next)
    {
        $user = auth()->user();

        if (!$user) {
            abort(403);
        }

        // Super admin can access everything
        if ($user->is_admin) {
            return $next($request);
        }

        // If user is org-admin, allow actions within their organization
        if ($user->is_org_admin) {
            $userOrgId = $user->organization_id;

            // If the route has an organization parameter, check it
            $orgParam = $request->route('organization');
            if ($orgParam) {
                $orgId = is_object($orgParam) ? ($orgParam->id ?? null) : $orgParam;
                if ($orgId == $userOrgId) {
                    return $next($request);
                }
                abort(403);
            }

            // If the route has a model parameter that belongs to an organization (client, farm, etc.)
            foreach ($request->route()->parameters() as $param) {
                if (is_object($param) && property_exists($param, 'organization_id')) {
                    if ($param->organization_id == $userOrgId) {
                        return $next($request);
                    }
                    abort(403);
                }
            }

            // For routes without model params (create/store), allow org-admin to proceed
            return $next($request);
        }

        abort(403);
    }
}
