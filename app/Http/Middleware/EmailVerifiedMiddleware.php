<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EmailVerifiedMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next)
    {
        // Verificar si el usuario no tiene el campo "email_verified_at" establecido
        if (empty($request->user()->email_verified_at)) {
            return redirect('https://mercado-country.vercel.app/'); // Editar ésta seccion para que redirija al user.confirm
        }

        return $next($request);
    }
}
