@extends('layouts.app')
@section('title', 'Inicio - Inmobiliaria Premium')

@section('content')
<section class="hero">
    <div class="hero-bg"></div>
    <div class="hero-content">
        <div class="hero-overline">— Inmobiliaria de Lujo en Colombia —</div>
        <h1 class="hero-title">FINCA RAÍZ</h1>
        <p class="hero-sub">Donde los sueños encuentran su dirección. Descubre propiedades exclusivas seleccionadas con la atención que mereces.</p>
        <div class="hero-cta">
            <a href="{{ route('propiedades.index') }}" class="btn btn-gold"><i class="fas fa-search"></i> Explorar Propiedades</a>
            <a href="{{ route('contacto') }}" class="btn btn-outline-gold"><i class="fas fa-phone"></i> Contactar Asesor</a>
        </div>
    </div>
    <a href="#search" class="scroll-indicator">Desliza<i class="fas fa-chevron-down"></i></a>
</section>

<div class="container" id="search">
    <div class="search-floating" data-aos="fade-up">
        <form action="{{ route('propiedades.index') }}" method="GET" class="row g-3 align-items-end">
            <div class="col-md-3"><label>Ciudad</label><input type="text" name="ciudad" placeholder="Ej: Bogotá"></div>
            <div class="col-md-3"><label>Tipo</label>
                <select name="tipo"><option value="">Todos</option>
                    @foreach(\App\Models\TipoInmueble::all() as $t)<option value="{{$t->id_tipo_inmueble}}">{{$t->nombre_tipo}}</option>@endforeach
                </select>
            </div>
            <div class="col-md-2"><label>Precio mín</label><input type="number" name="min" placeholder="0"></div>
            <div class="col-md-2"><label>Precio máx</label><input type="number" name="max" placeholder="∞"></div>
            <div class="col-md-2"><button class="btn btn-gold w-100"><i class="fas fa-search"></i> Buscar</button></div>
        </form>
    </div>
</div>

<section class="block">
    <div class="container">
        <div class="section-title" data-aos="fade-up">
            <div class="overline">Selección Exclusiva</div>
            <h2>Propiedades Destacadas</h2>
            <div class="divider-gold"></div>
            <p>Una colección curada de las propiedades más exclusivas que ofrecen experiencias de vida únicas.</p>
        </div>
        <div class="swiper swiper-destacadas">
            <div class="swiper-wrapper">
                @foreach($destacadas as $inmueble)
                    <div class="swiper-slide">@include('components.property-card', ['inmueble' => $inmueble])</div>
                @endforeach
            </div>
            <div class="swiper-button-prev" style="color:#c9a14a"></div>
            <div class="swiper-button-next" style="color:#c9a14a"></div>
        </div>
    </div>
</section>

<section class="stats-row">
    <div class="container">
        <div class="row g-4">
            <div class="col-md-3 col-6 stat-item" data-aos="zoom-in"><div class="stat-num" data-count="{{ $stats['inmuebles'] }}">0</div><div class="stat-label">Inmuebles</div></div>
            <div class="col-md-3 col-6 stat-item" data-aos="zoom-in" data-aos-delay="100"><div class="stat-num" data-count="{{ $stats['clientes'] }}">0</div><div class="stat-label">Clientes Felices</div></div>
            <div class="col-md-3 col-6 stat-item" data-aos="zoom-in" data-aos-delay="200"><div class="stat-num" data-count="{{ $stats['vendidas'] }}">0</div><div class="stat-label">Vendidas</div></div>
            <div class="col-md-3 col-6 stat-item" data-aos="zoom-in" data-aos-delay="300"><div class="stat-num" data-count="{{ $stats['arrendadas'] }}">0</div><div class="stat-label">Arrendadas</div></div>
        </div>
    </div>
</section>

<section class="block gray">
    <div class="container">
        <div class="section-title" data-aos="fade-up">
            <div class="overline">Recién Llegadas</div>
            <h2>Propiedades Recientes</h2>
            <div class="divider-gold"></div>
        </div>
        <div class="row g-4">
            @foreach($recientes as $inmueble)
                <div class="col-lg-3 col-md-6" data-aos="fade-up" data-aos-delay="{{ $loop->index * 60 }}">
                    @include('components.property-card', ['inmueble' => $inmueble])
                </div>
            @endforeach
        </div>
        <div class="text-center mt-5"><a href="{{ route('propiedades.index') }}" class="btn btn-gold">Ver todas <i class="fas fa-arrow-right"></i></a></div>
    </div>
</section>

<section class="block">
    <div class="container">
        <div class="section-title" data-aos="fade-up">
            <div class="overline">Nuestra Filosofía</div>
            <h2>Misión, Visión y Valores</h2>
            <div class="divider-gold"></div>
        </div>
        <div class="row g-4">
            <div class="col-md-4" data-aos="fade-up"><div class="value-card text-center">
                <div class="value-icon mx-auto"><i class="fas fa-bullseye"></i></div>
                <h4>Misión</h4>
                <p>Conectar personas con propiedades extraordinarias, ofreciendo asesoría profesional y experiencias inmobiliarias premium.</p>
            </div></div>
            <div class="col-md-4" data-aos="fade-up" data-aos-delay="120"><div class="value-card text-center">
                <div class="value-icon mx-auto"><i class="fas fa-eye"></i></div>
                <h4>Visión</h4>
                <p>Ser la inmobiliaria líder en Colombia reconocida por la elegancia, transparencia y excelencia en cada transacción.</p>
            </div></div>
            <div class="col-md-4" data-aos="fade-up" data-aos-delay="240"><div class="value-card text-center">
                <div class="value-icon mx-auto"><i class="fas fa-gem"></i></div>
                <h4>Valores</h4>
                <p>Integridad, calidad, innovación, compromiso con el cliente y pasión por crear hogares y oportunidades de inversión.</p>
            </div></div>
        </div>
    </div>
</section>

<section class="block gray">
    <div class="container">
        <div class="section-title" data-aos="fade-up">
            <div class="overline">Lo que dicen</div>
            <h2>Opiniones de Clientes</h2>
            <div class="divider-gold"></div>
        </div>
        <div class="swiper swiper-testimonios">
            <div class="swiper-wrapper">
                @php $opiniones = [
                    ['nombre'=>'Andrea López','foto'=>'https://i.pravatar.cc/150?img=47','texto'=>'Servicio impecable. Encontré mi apartamento ideal en menos de dos semanas. ¡Altamente recomendados!'],
                    ['nombre'=>'Carlos Méndez','foto'=>'https://i.pravatar.cc/150?img=12','texto'=>'Profesionalismo de principio a fin. La asesoría jurídica y financiera fue clave para mi inversión.'],
                    ['nombre'=>'Valeria Restrepo','foto'=>'https://i.pravatar.cc/150?img=32','texto'=>'Las propiedades son de altísima categoría. Una experiencia premium en cada visita.'],
                    ['nombre'=>'Sebastián Ruiz','foto'=>'https://i.pravatar.cc/150?img=15','texto'=>'Excelente atención y un equipo siempre dispuesto a ayudar. Volveré sin dudarlo.'],
                ]; @endphp
                @foreach($opiniones as $op)
                    <div class="swiper-slide"><div class="testimonial">
                        <img src="{{ $op['foto'] }}" alt="{{ $op['nombre'] }}">
                        <div class="testimonial-stars">★★★★★</div>
                        <p>"{{ $op['texto'] }}"</p>
                        <h6 class="mt-3 mb-0">{{ $op['nombre'] }}</h6>
                        <small class="text-muted">Cliente verificado</small>
                    </div></div>
                @endforeach
            </div>
            <div class="swiper-pagination mt-4"></div>
        </div>
    </div>
</section>

<section class="block dark">
    <div class="container text-center">
        <h2 data-aos="fade-up">¿Listo para encontrar tu próximo <span class="gold">hogar de ensueño</span>?</h2>
        <p class="mt-3 mb-4" data-aos="fade-up" data-aos-delay="100">Agenda una cita con nuestros asesores expertos.</p>
        <a href="{{ route('contacto') }}" class="btn btn-gold" data-aos="fade-up" data-aos-delay="200"><i class="fas fa-calendar"></i> Agendar Cita</a>
    </div>
</section>
@endsection
