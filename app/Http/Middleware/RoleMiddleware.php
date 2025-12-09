<?php

namespace App\Http\Middleware;

use App\Models\Role;
use Closure;
use Illuminate\Http\Request;
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
        dd('hello');
        $userRole = $request->user()->getRoleName();

        if (!in_array($userRole, $roles)) {
            abort(403, 'Unauthorized access.');
        }
        // dd($userRole, $roles);

        return $next($request);
    }
}
