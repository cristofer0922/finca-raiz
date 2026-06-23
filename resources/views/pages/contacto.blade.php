@extends('layouts.app')
@section('title', 'Contacto')
@section('content')
<section style="padding-top:140px" class="bg-dark text-white pb-4">
    <div class="container text-center">
        <h1 class="serif" data-aos="fade-up">Contáctanos</h1>
        <p class="text-muted">Estamos aquí para asesorarte</p>
    </div>
</section>
<div class="container my-5">
    <div class="row g-5">
        <div class="col-md-5" data-aos="fade-right">
            <div class="admin-card">
                <h4 class="serif"><i class="fas fa-map-marker-alt text-gold"></i> Ubicación</h4>
                <p>Carrera 11 #82-71, Bogotá - Colombia</p>
                <h4 class="serif"><i class="fas fa-phone text-gold"></i> Teléfono</h4>
                <p>+57 310 123 4567</p>
                <h4 class="serif"><i class="fas fa-envelope text-gold"></i> Correo</h4>
                <p>info@fincaraiz.com</p>
                <h4 class="serif"><i class="fas fa-clock text-gold"></i> Horario</h4>
                <p>Lun - Sáb · 8:00 AM - 7:00 PM</p>
            </div>
        </div>
        <div class="col-md-7" data-aos="fade-left">
            <div class="form-card">
                <h3 class="serif mb-4">Envíanos un mensaje</h3>
                <form method="POST" action="{{ route('solicitud.store') }}" class="row g-3">@csrf
                    <input type="hidden" name="tipo_solicitud" value="visita">
                    <div class="col-md-6"><label>Nombre</label><input class="form-control" name="p_nombre" required></div>
                    <div class="col-md-6"><label>Apellido</label><input class="form-control" name="p_apellido" required></div>
                    <div class="col-md-6"><label>Documento</label><input class="form-control" name="documento" required></div>
                    <div class="col-md-6"><label>Teléfono</label><input class="form-control" name="celular" required></div>
                    <div class="col-12"><label>Correo</label><input class="form-control" type="email" name="correo" required></div>
                    <div class="col-12"><label>Mensaje</label><textarea class="form-control" name="mensaje" rows="5" required></textarea></div>
                    <div class="col-12"><button class="btn btn-gold w-100"><i class="fas fa-paper-plane"></i> Enviar</button></div>
                </form>
            </div>
        </div>
    </div>
    <div class="admin-card mt-5">
        <iframe width="100%" height="400" style="border:0;border-radius:14px" loading="lazy" allowfullscreen
            src="https://www.google.com/maps?q=Bogota,Colombia&hl=es&z=12&output=embed"></iframe>
    </div>
</div>
@endsection
