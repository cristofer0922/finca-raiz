<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Cliente;

class AuthController extends Controller
{
    public function showLogin()    { return view('auth.login'); }
    public function showRegister() { return view('auth.register'); }
    public function showForgot()   { return view('auth.forgot'); }

    public function login(Request $r)
    {
        $r->validate([
            'correo' => 'required|email',
            'contrasena' => 'required',
        ]);

        $user = User::where('correo', $r->correo)->with('tipo')->first();
        if (!$user || !Hash::check($r->contrasena, $user->contrasena)) {
            return back()->with('error', 'Credenciales inválidas')->withInput();
        }
        if ($user->estado !== 'activo') {
            return back()->with('error', 'Cuenta inactiva o bloqueada');
        }

        $rol = $user->tipo->nombre_tipo ?? 'Cliente';

        session(['user' => [
            'id' => $user->id_usuario,
            'usuario' => $user->usuario,
            'correo' => $user->correo,
            'rol' => $rol,
        ]]);

        $user->ultimo_acceso = now();
        $user->save();

        if ($rol === 'Administrador')          return redirect()->route('admin.dashboard');
        if (in_array($rol, ['Agente','Asesor'])) return redirect()->route('agente.dashboard');
        return redirect()->route('home')->with('success', 'Bienvenido '.$user->usuario);
    }

    public function register(Request $r)
    {
        $r->validate([
            'usuario' => 'required|min:3|max:50|unique:usuarios,usuario',
            'correo' => 'required|email|unique:usuarios,correo',
            'contrasena' => 'required|min:6|confirmed',
        ]);

        $user = User::create([
            'usuario' => $r->usuario,
            'correo' => $r->correo,
            'contrasena' => Hash::make($r->contrasena),
            'id_tipo_usuario' => 3,
            'estado' => 'activo',
        ]);

        return redirect()->route('login')->with('success', 'Cuenta creada, ya puedes iniciar sesión');
    }

    public function logout(Request $r)
    {
        session()->forget('user');
        return redirect()->route('home');
    }

    public function forgot(Request $r)
    {
        $r->validate(['correo' => 'required|email']);
        return back()->with('success', 'Si el correo existe enviaremos instrucciones.');
    }
}
