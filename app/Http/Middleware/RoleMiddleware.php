<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next)
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $role = Auth::user()->role;
        $uri = $request->path();

        $blockedUris = [
            'user-management',
        ];

        if ($role == 'user') {
            foreach ($blockedUris as $blockedUri) {
                if (str_contains($uri, $blockedUri)) {
                    abort(403, 'Unauthorized access');
                }
            }
        }

        return $next($request);
    }
}
