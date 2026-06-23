@extends('layouts.app')
@section('title','Pago exitoso')
@section('content')
<section style="padding-top:140px" class="bg-dark text-white pb-5"><div class="container text-center">
<i class="fas fa-circle-check text-gold" style="font-size:80px"></i>
<h1 class="serif mt-3">¡Pago aprobado!</h1>
<p class="text-muted">Tu transacción fue procesada correctamente.</p>
<a href="{{ route('home') }}" class="btn btn-gold mt-3">Volver al inicio</a>
</div></section>
@endsection
