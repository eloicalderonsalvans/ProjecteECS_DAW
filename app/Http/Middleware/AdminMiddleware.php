<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Middleware que restringeix l'accés exclusivament als usuaris amb rol 'admin'.
 * Si l'usuari no és administrador, redirigeix al dashboard amb un missatge d'error.
 */
class AdminMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        if (! $request->user() || $request->user()->role !== 'admin') {
            return redirect()->route('dashboard')->with('error', 'No tens permisos per accedir a aquesta secció.');
        }

        return $next($request);
    }
}
