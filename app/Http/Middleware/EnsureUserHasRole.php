<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserHasRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string  ...$roles
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $user = Auth::user();

        // Admin has access to everything
        if ($user->isAdmin()) {
            return $next($request);
        }

        // Check if user has any of the required roles
        foreach ($roles as $role) {
            if ($user->role->value === $role) {
                return $next($request);
            }
        }

        abort(403, 'Anda tidak memiliki hak akses untuk halaman ini.');
    }
}
