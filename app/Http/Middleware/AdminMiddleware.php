<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        dd('check');
        if (!auth()->check()) {
            abort(403, 'Unauthorized access. Please login.');
        }

        $user = auth()->user();
        
        // Check if user has role_id
        if (!$user->role_id) {
            Log::error('User without role_id trying to access admin', ['user_id' => $user->id]);
            abort(403, 'Unauthorized access. No role assigned.');
        }

        // Check if role exists
        if (!$user->role) {
            Log::error('User role not found', ['user_id' => $user->id, 'role_id' => $user->role_id]);
            abort(403, 'Unauthorized access. Role not found.');
        }

        if (!$user->isAdmin()) {
            Log::warning('Non-admin user trying to access admin', ['user_id' => $user->id, 'role' => $user->getRoleName()]);
            abort(403, 'Unauthorized access. Admin privileges required.');
        }

        return $next($request);
    }
}
