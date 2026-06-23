@extends('layouts.app')
@section('title','Pago rechazado')
@section('content')
<section style="padding-top:140px" class="bg-dark text-white pb-5"><div class="container text-center">
<i class="fas fa-circle-xmark text-gold" style="font-size:80px"></i>
<h1 class="serif mt-3">El pago no se completó</h1>
<a href="{{ route('planes.index') }}" class="btn btn-gold mt-3">Reintentar</a>
</div></section>
@endsection
