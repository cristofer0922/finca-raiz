<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class AdminMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        $user = session('user');
        if (!$user) {
            return redirect()->route('login')->with('error', 'Debes iniciar sesión.');
        }
        if (($user['rol'] ?? '') !== 'Administrador') {
            return redirect()->route('home')->with('error', 'Acceso restringido.');
        }
        return $next($request);
    }
}
