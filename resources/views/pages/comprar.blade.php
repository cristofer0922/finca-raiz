@extends('layouts.app')
@section('title', 'Comprar')
@section('content')
<section style="padding-top:140px" class="bg-dark text-white pb-4">
    <div class="container text-center">
        <h1 class="serif" data-aos="fade-up">Compra tu <span class="gold">Hogar Ideal</span></h1>
        <p class="text-muted">Inversión inteligente en bienes raíces de lujo</p>
    </div>
</section>
<div class="container my-5">
    <div class="row g-4 mb-5">
        @foreach($inmuebles as $inmueble)
            <div class="col-lg-4 col-md-6" data-aos="fade-up">@include('components.property-card', ['inmueble' => $inmueble])</div>
        @endforeach
    </div>

    <div class="form-card mt-5" id="comprar-form" data-aos="fade-up">
        <h3 class="serif text-center mb-4">Solicita tu compra</h3>
        <form method="POST" action="{{ route('solicitud.store') }}" class="row g-3">@csrf
            <input type="hidden" name="tipo_solicitud" value="compra">
            <div class="col-md-6"><label>Primer nombre</label><input class="form-control" name="p_nombre" required></div>
            <div class="col-md-6"><label>Primer apellido</label><input class="form-control" name="p_apellido" required></div>
            <div class="col-md-6"><label>Documento</label><input class="form-control" name="documento" required></div>
            <div class="col-md-6"><label>Teléfono</label><input class="form-control" name="celular" required></div>
            <div class="col-md-6"><label>Correo</label><input class="form-control" type="email" name="correo" required></div>
            <div class="col-md-6"><label>Dirección</label><input class="form-control" name="direccion"></div>
            <div class="col-md-6"><label>Ciudad</label><input class="form-control" name="ciudad"></div>
            <div class="col-md-6"><label>Fecha preferida</label><input class="form-control" type="date" name="fecha"></div>
            <div class="col-12"><label>Mensaje</label><textarea class="form-control" name="mensaje" rows="4"></textarea></div>
            <div class="col-12 text-center"><button class="btn btn-gold"><i class="fas fa-paper-plane"></i> Enviar solicitud</button></div>
        </form>
    </div>
    <div class="mt-4 d-flex justify-content-center">{{ $inmuebles->links('pagination::bootstrap-5') }}</div>
</div>
@endsection
