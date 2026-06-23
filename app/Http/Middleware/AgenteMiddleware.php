<?php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class AgenteMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        $user = session('user');
        if (!$user) {
            return redirect()->route('login')->with('error', 'Debes iniciar sesión.');
        }
        $rol = $user['rol'] ?? '';
        if (!in_array($rol, ['Agente','Asesor','Administrador'])) {
            return redirect()->route('home')->with('error', 'Acceso restringido al panel de agente.');
        }
        return $next($request);
    }
}
