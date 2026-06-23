@extends('layouts.app')
@section('title','Crear cuenta')
@section('content')
<div class="auth-wrapper">
    <div class="auth-card" data-aos="zoom-in">
        <div class="auth-logo"><div class="brand-logo">FR</div><h3 class="serif mt-3">Crear Cuenta</h3></div>
        <form method="POST" action="{{ route('register.post') }}">@csrf
            <div class="mb-3"><label>Usuario</label><input class="form-control" name="usuario" required></div>
            <div class="mb-3"><label>Correo</label><input class="form-control" type="email" name="correo" required></div>
            <div class="mb-3"><label>Contraseña</label><input class="form-control" type="password" name="contrasena" required></div>
            <div class="mb-3"><label>Confirmar contraseña</label><input class="form-control" type="password" name="contrasena_confirmation" required></div>
            <button class="btn btn-gold w-100">Crear cuenta</button>
            <hr>
            <p class="text-center mb-0">¿Ya tienes cuenta? <a href="{{ route('login') }}" class="text-gold">Inicia sesión</a></p>
        </form>
    </div>
</div>
@endsection
