<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'FincaRaíz') | Inmobiliaria de Lujo</title>
    <meta name="description" content="@yield('description','FincaRaíz - Encuentra propiedades exclusivas de lujo en Colombia. Compra y arrienda tu próximo hogar.')">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;700;900&family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
    <link href="https://unpkg.com/aos@2.3.4/dist/aos.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css" rel="stylesheet">

    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    @stack('styles')
</head>
<body>

<div id="page-loader">
    <div class="loader-content">
        <div class="loader-logo">FR</div>
        <div class="loader-bar"><span></span></div>
        <p>Cargando experiencia premium...</p>
    </div>
</div>

<nav class="navbar navbar-expand-lg fixed-top navbar-finca" id="mainNav">
    <div class="container">
        <a class="navbar-brand" href="{{ route('home') }}">
            <span class="brand-logo">FR</span>
            <span class="brand-text">Finca<strong>Raíz</strong></span>
        </a>
        <button class="navbar-toggler" data-bs-toggle="collapse" data-bs-target="#navMenu">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navMenu">
            <ul class="navbar-nav ms-auto align-items-lg-center">
                <li class="nav-item"><a class="nav-link" href="{{ route('home') }}">Inicio</a></li>
                <li class="nav-item"><a class="nav-link" href="{{ route('propiedades.index') }}">Propiedades</a></li>
                <li class="nav-item"><a class="nav-link" href="{{ route('comprar') }}">Comprar</a></li>
                <li class="nav-item"><a class="nav-link" href="{{ route('arrendar') }}">Arrendar</a></li>
                <li class="nav-item"><a class="nav-link" href="{{ route('nosotros') }}">Nosotros</a></li>
                <li class="nav-item"><a class="nav-link" href="{{ route('contacto') }}">Contacto</a></li>
                @if(session('user'))
                    @if(session('user.rol') === 'Administrador')
                        <li class="nav-item ms-lg-2"><a class="btn btn-gold btn-sm" href="{{ route('admin.dashboard') }}"><i class="fas fa-cog"></i> Panel</a></li>
                    @endif
                    <li class="nav-item ms-lg-2">
                        <form action="{{ route('logout') }}" method="POST" class="d-inline">@csrf
                            <button class="btn btn-outline-light btn-sm">{{ session('user.usuario') }} · Salir</button>
                        </form>
                    </li>
                @else
                    <li class="nav-item ms-lg-2"><a class="btn btn-gold btn-sm" href="{{ route('login') }}"><i class="fas fa-user"></i> Ingresar</a></li>
                @endif
            </ul>
        </div>
    </div>
</nav>

<main>
    @if(session('success'))
        <div class="alert-flash" data-type="success" data-msg="{{ session('success') }}"></div>
    @endif
    @if(session('error'))
        <div class="alert-flash" data-type="error" data-msg="{{ session('error') }}"></div>
    @endif

    @yield('content')
</main>

<footer class="footer-finca">
    <div class="container">
        <div class="row g-4">
            <div class="col-lg-4">
                <h4 class="footer-brand">Finca<span>Raíz</span></h4>
                <p>Vivimos los bienes raíces como un arte. Más de 15 años conectando personas con su hogar ideal en Colombia.</p>
                <div class="social-links">
                    <a href="#"><i class="fab fa-facebook-f"></i></a>
                    <a href="#"><i class="fab fa-instagram"></i></a>
                    <a href="#"><i class="fab fa-twitter"></i></a>
                    <a href="#"><i class="fab fa-linkedin-in"></i></a>
                    <a href="#"><i class="fab fa-youtube"></i></a>
                </div>
            </div>
            <div class="col-lg-2 col-md-4">
                <h6>Enlaces</h6>
                <ul><li><a href="{{ route('home') }}">Inicio</a></li>
                <li><a href="{{ route('propiedades.index') }}">Propiedades</a></li>
                <li><a href="{{ route('comprar') }}">Comprar</a></li>
                <li><a href="{{ route('arrendar') }}">Arrendar</a></li></ul>
            </div>
            <div class="col-lg-3 col-md-4">
                <h6>Contacto</h6>
                <ul><li><i class="fas fa-map-marker-alt"></i> Cra 11 #82-71, Bogotá</li>
                <li><i class="fas fa-phone"></i> +57 310 123 4567</li>
                <li><i class="fas fa-envelope"></i> info@fincaraiz.com</li></ul>
            </div>
            <div class="col-lg-3 col-md-4">
                <h6>Newsletter</h6>
                <p>Recibe propiedades exclusivas en tu correo</p>
                <form class="newsletter-form" onsubmit="event.preventDefault(); Swal.fire({icon:'success',title:'¡Suscrito!',timer:1500,showConfirmButton:false})">
                    <input type="email" placeholder="tu@email.com" required>
                    <button type="submit"><i class="fas fa-paper-plane"></i></button>
                </form>
            </div>
        </div>
        <hr>
        <div class="text-center">&copy; {{ date('Y') }} FincaRaízPro · Todos los derechos reservados · <span class="gold">Diseñado con elegancia</span></div>
    </div>
</footer>

<a href="#" id="back-to-top"><i class="fas fa-arrow-up"></i></a>

<div id="chat-widget">
    <button id="chat-toggle"><i class="fas fa-comments"></i></button>
    <div id="chat-box">
            <div class="chat-header"><i class="fas fa-headset"></i> Atención FincaRaíz</div>
        <div class="chat-body">
            <div class="chat-msg bot">¡Hola! 👋 ¿En qué podemos ayudarte hoy?</div>
        </div>
        <div class="chat-input">
            <input type="text" id="chat-text" placeholder="Escribe tu mensaje...">
            <button id="chat-send"><i class="fas fa-paper-plane"></i></button>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://unpkg.com/aos@2.3.4/dist/aos.js"></script>
<script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="{{ asset('js/app.js') }}"></script>
@stack('scripts')
</body>
</html>
