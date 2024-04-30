<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Auth\Middleware\Authenticate as Middleware;

class Authenticate extends Middleware
{
    /**
     * Get the path the user should be redirected to when they are not authenticated.
     */
    protected function redirectTo(Request $request): ?string
    {
        return $request->expectsJson() ? null : route('login');
    }

    public function handle($request, Closure $next, ...$guards)
    {
        if (Auth::check()) {
            // Si l'utilisateur est connecté, continuez vers la requête suivante
            return $next($request);
        }

        // Si l'utilisateur n'est pas connecté, redirigez vers la page de connexion
        return redirect('/auth/google');
    }
}
