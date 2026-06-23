@extends('layouts.app')
@section('title','Recuperar contraseña')
@section('content')
<div class="auth-wrapper">
    <div class="auth-card">
        <h3 class="serif text-center">Recuperar contraseña</h3>
        <p class="text-muted text-center">Te enviaremos instrucciones</p>
        <form method="POST" action="{{ route('password.forgot.post') }}">@csrf
            <div class="mb-3"><label>Correo</label><input class="form-control" type="email" name="correo" required></div>
            <button class="btn btn-gold w-100">Enviar</button>
            <p class="text-center mt-3"><a href="{{ route('login') }}" class="text-gold small">Volver al login</a></p>
        </form>
    </div>
</div>
@endsection
