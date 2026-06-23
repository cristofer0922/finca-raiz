@extends('layouts.app')
@section('title','Pago pendiente')
@section('content')
<section style="padding-top:140px" class="bg-dark text-white pb-5"><div class="container text-center">
<i class="fas fa-clock text-gold" style="font-size:80px"></i>
<h1 class="serif mt-3">Pago pendiente de confirmación</h1>
<a href="{{ route('home') }}" class="btn btn-gold mt-3">Volver al inicio</a>
</div></section>
@endsection
