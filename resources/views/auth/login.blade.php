@extends('layouts.app')
@section('title','Iniciar sesión')
@section('content')
<div class="auth-wrapper">
    <div class="auth-card" data-aos="zoom-in">
        <div class="auth-logo"><div class="brand-logo">FR</div><h3 class="serif mt-3">Bienvenido</h3><p class="text-muted">Ingresa a tu cuenta</p></div>
        <form method="POST" action="{{ route('login.post') }}">@csrf
            <div class="mb-3"><label>Correo</label><input class="form-control" type="email" name="correo" value="{{ old('correo') }}" required></div>
            <div class="mb-3"><label>Contraseña</label><input class="form-control" type="password" name="contrasena" required></div>
            <button class="btn btn-gold w-100">Iniciar Sesión</button>
            <p class="text-center mt-3"><a href="{{ route('password.forgot') }}" class="text-muted small">¿Olvidaste tu contraseña?</a></p>
            <hr>
            <p class="text-center mb-0">¿No tienes cuenta? <a href="{{ route('register') }}" class="text-gold">Regístrate</a></p>
        </form>
        <div class="alert alert-light small mt-3">
            <strong>Demo:</strong><br>
            Admin: <code>admin@fincaraiz.com</code> / <code>admin123</code><br>
            Cliente: <code>cliente@fincaraiz.com</code> / <code>cliente123</code>
        </div>
    </div>
</div>
@endsection
