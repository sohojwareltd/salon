<?php

namespace App\Http\Middleware;

use App\Models\Role;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        if (!$request->user()) {
            return redirect()->route('login');
        }
        
        $user = $request->user();
        
        // Check if user has role_id
        if (!$user->role_id) {
            Log::error('User without role_id', ['user_id' => $user->id, 'required_roles' => $roles]);
            abort(403, 'Unauthorized access. No role assigned.');
        }
        
        $userRole = $user->getRoleName();
        
        if (!$userRole) {
            Log::error('Could not get role name', ['user_id' => $user->id, 'role_id' => $user->role_id]);
            abort(403, 'Unauthorized access. Invalid role.');
        }

        if (!in_array($userRole, $roles)) {
            Log::warning('User role mismatch', ['user_id' => $user->id, 'user_role' => $userRole, 'required_roles' => $roles]);
            abort(403, 'Unauthorized access. Required roles: ' . implode(', ', $roles));
        }

        return $next($request);
    }
}
