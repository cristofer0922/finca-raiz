<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title','Panel') | FincaRaíz Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700;900&family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
</head>
<body>
<div class="admin-wrap">
    <aside class="admin-sidebar">
            <div class="logo"><span>Finca</span><strong style="color:#fff">Raíz</strong><br><small style="color:#888;font-size:.7rem;letter-spacing:2px">ADMIN PANEL</small></div>
        <a href="{{ route('admin.dashboard') }}" class="{{ request()->routeIs('admin.dashboard')?'active':'' }}"><i class="fas fa-th-large"></i> Dashboard</a>
        <a href="{{ route('admin.propiedades.index') }}" class="{{ request()->routeIs('admin.propiedades.*')?'active':'' }}"><i class="fas fa-building"></i> Propiedades</a>
        <a href="{{ route('admin.clientes.index') }}" class="{{ request()->routeIs('admin.clientes.*')?'active':'' }}"><i class="fas fa-users"></i> Clientes</a>
        <a href="{{ route('admin.usuarios.index') }}" class="{{ request()->routeIs('admin.usuarios.*')?'active':'' }}"><i class="fas fa-user-shield"></i> Usuarios</a>
        <a href="{{ route('admin.solicitudes.index') }}" class="{{ request()->routeIs('admin.solicitudes.*')?'active':'' }}"><i class="fas fa-inbox"></i> Solicitudes</a>
        <a href="{{ route('home') }}"><i class="fas fa-globe"></i> Ver sitio</a>
        <form action="{{ route('logout') }}" method="POST">@csrf<button type="submit" style="background:none;border:none;color:#bbb;padding:13px 24px;width:100%;text-align:left"><i class="fas fa-sign-out-alt"></i> Cerrar sesión</button></form>
    </aside>
    <div class="admin-main">
        <div class="admin-topbar">
            <div>
                <button id="sidebar-toggle" class="btn btn-sm btn-dark d-lg-none"><i class="fas fa-bars"></i></button>
                <h5 class="serif d-inline ms-2 mb-0">@yield('page','Dashboard')</h5>
            </div>
            <div><span class="text-muted small">Hola, </span><strong>{{ session('user.usuario') }}</strong> <span class="badge bg-warning text-dark">{{ session('user.rol') }}</span></div>
        </div>
        @if(session('success'))<div class="alert-flash" data-type="success" data-msg="{{ session('success') }}"></div>@endif
        @if(session('error'))<div class="alert-flash" data-type="error" data-msg="{{ session('error') }}"></div>@endif
        @yield('content')
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="{{ asset('js/app.js') }}"></script>
@stack('scripts')
</body>
</html>
