@extends('layouts.app')
@section('title', 'Nosotros')
@section('content')
<section style="padding-top:140px;background:url('https://images.unsplash.com/photo-1497366216548-37526070297c?w=1920') center/cover" class="text-white">
    <div style="background:rgba(0,0,0,.7);padding:80px 0">
        <div class="container text-center">
            <h1 class="serif" data-aos="fade-up">Sobre <span class="gold">FincaRaíz</span></h1>
            <p class="text-muted">15 años creando experiencias inmobiliarias premium</p>
        </div>
    </div>
</section>
<div class="container my-5">
    <div class="row align-items-center g-5">
        <div class="col-md-6" data-aos="fade-right">
            <img src="https://images.unsplash.com/photo-1486406146926-c627a92ad1ab?w=900" class="img-fluid rounded-4">
        </div>
        <div class="col-md-6" data-aos="fade-left">
            <div class="overline gold" style="letter-spacing:5px;font-size:.8rem;text-transform:uppercase">Nuestra historia</div>
                <h2 class="serif">Excelencia en cada propiedad</h2>
            <p>Desde 2009 hemos sido pioneros en el sector inmobiliario premium de Colombia. Nuestro equipo de asesores certificados y aliados estratégicos nos permite ofrecer el mejor servicio del mercado.</p>
            <p>Trabajamos con propietarios, inversionistas y familias para conectar sueños con realidades de vivienda.</p>
            <a href="{{ route('contacto') }}" class="btn btn-gold mt-3">Habla con nosotros</a>
        </div>
    </div>
</div>
<section class="block gray">
    <div class="container">
        <div class="section-title"><div class="overline">Equipo</div><h2>Nuestros Asesores Expertos</h2><div class="divider-gold"></div></div>
        <div class="row g-4">
            @foreach([['Laura Pérez','Directora Comercial',5],['Andrés Suárez','Asesor Senior',12],['Mariana Vega','Asesora Premium',47],['Felipe Torres','Asesor Inversiones',23]] as $a)
                <div class="col-md-3 col-6" data-aos="fade-up">
                    <div class="testimonial">
                        <img src="https://i.pravatar.cc/150?img={{$a[2]}}">
                        <h6 class="serif mb-0">{{$a[0]}}</h6>
                        <small class="text-muted">{{$a[1]}}</small>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>
@endsection
