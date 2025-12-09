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
        // Get authenticated user from any guard
        $user = auth()->user() ?? auth()->guard('web')->user();
        
        if (!$user) {
            Log::error('No authenticated user in AdminMiddleware');
            return redirect()->route('login');
        }
        
        // Refresh user from database to ensure we have latest data including relationships
        $user = $user->fresh(['role']);
        
        // Check if user has role_id
        if (!$user->role_id) {
            Log::error('User without role_id trying to access admin', [
                'user_id' => $user->id,
                'email' => $user->email
            ]);
            abort(403, 'Unauthorized access. No role assigned. Please contact administrator.');
        }

        // Check if role exists
        if (!$user->role) {
            Log::error('User role not found', [
                'user_id' => $user->id,
                'email' => $user->email,
                'role_id' => $user->role_id
            ]);
            abort(403, 'Unauthorized access. Role not found. Please contact administrator.');
        }

        $roleName = $user->role->name ?? null;
        
        if ($roleName !== 'admin') {
            Log::warning('Non-admin user trying to access admin panel', [
                'user_id' => $user->id,
                'email' => $user->email,
                'role' => $roleName
            ]);
            abort(403, 'Unauthorized access. Admin privileges required. Your role: ' . $roleName);
        }

        return $next($request);
    }
}
