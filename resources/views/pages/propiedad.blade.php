@extends('layouts.app')
@section('title', $inmueble->titulo)
@php
    $wa = app(\App\Services\WhatsappService::class)->linkPropiedad($inmueble);
    $gmapsKey = config('services.google.maps_key');
@endphp
@section('content')
<section style="padding-top:140px" class="bg-dark text-white pb-4">
    <div class="container">
        <a href="{{ route('propiedades.index') }}" class="text-gold text-decoration-none"><i class="fas fa-arrow-left"></i> Volver</a>
        <h1 class="serif mt-2" data-aos="fade-up">{{ $inmueble->titulo }}</h1>
        <p class="text-muted"><i class="fas fa-map-marker-alt text-gold"></i> {{ $inmueble->direccion }}, {{ $inmueble->ciudad }} · <span class="badge bg-secondary">{{ $inmueble->estado_propiedad }}</span></p>
    </div>
</section>

<div class="container my-5">
    <div class="row g-5">
        <div class="col-lg-8">
            @php $galeria = $inmueble->imagenesHd->count() ? $inmueble->imagenesHd : $inmueble->imagenes; @endphp
            <div class="gallery-main">
                <img src="{{ $galeria->first()->url ?? $galeria->first()->url_imagen ?? $inmueble->imagen_principal }}" alt="">
            </div>
            <div class="gallery-thumbs">
                @foreach($galeria as $i => $img)
                    <img src="{{ $img->url ?? $img->url_imagen }}" class="{{ $i===0?'active':'' }}">
                @endforeach
            </div>

            @if($inmueble->tour_virtual)
                <div class="admin-card mt-4">
                    <h3 class="serif">Tour virtual</h3>
                    <iframe src="{{ $inmueble->tour_virtual }}" width="100%" height="450" style="border:0;border-radius:14px" allowfullscreen></iframe>
                </div>
            @endif

            @if($inmueble->video_url)
                <div class="admin-card mt-4">
                    <h3 class="serif">Video</h3>
                    <iframe src="{{ $inmueble->video_url }}" width="100%" height="400" style="border:0;border-radius:14px" allowfullscreen></iframe>
                </div>
            @endif

            <div class="admin-card mt-4">
                <h3 class="serif">Descripción</h3>
                <p>{{ $inmueble->descripcion }}</p>
                <div class="row text-center mt-4">
                    <div class="col"><i class="fas fa-bed text-gold fs-3"></i><div class="mt-2"><strong>{{$inmueble->habitaciones}}</strong><br><small>Habitaciones</small></div></div>
                    <div class="col"><i class="fas fa-bath text-gold fs-3"></i><div class="mt-2"><strong>{{$inmueble->banos}}</strong><br><small>Baños</small></div></div>
                    <div class="col"><i class="fas fa-car text-gold fs-3"></i><div class="mt-2"><strong>{{$inmueble->garajes}}</strong><br><small>Parqueaderos</small></div></div>
                    <div class="col"><i class="fas fa-ruler-combined text-gold fs-3"></i><div class="mt-2"><strong>{{$inmueble->area}}m²</strong><br><small>Área</small></div></div>
                    <div class="col"><i class="fas fa-layer-group text-gold fs-3"></i><div class="mt-2"><strong>{{$inmueble->estrato}}</strong><br><small>Estrato</small></div></div>
                </div>
            </div>

            @if($inmueble->latitud && $inmueble->longitud)
                <div class="admin-card mt-4">
                    <h3 class="serif">Ubicación</h3>
                    @if($gmapsKey)
                        <div id="propMap" style="width:100%;height:400px;border-radius:14px"></div>
                        <div class="mt-3">
                            <h5 class="serif">Propiedades cercanas</h5>
                            <div id="cercanasList" class="text-muted small">Buscando...</div>
                        </div>
                    @else
                        <iframe width="100%" height="350" style="border:0;border-radius:14px" loading="lazy" allowfullscreen
                            src="https://www.google.com/maps?q={{ $inmueble->latitud }},{{ $inmueble->longitud }}&hl=es&z=15&output=embed"></iframe>
                    @endif
                </div>
            @endif
        </div>

        <div class="col-lg-4">
            <div class="form-card">
                <div class="text-center mb-3">
                    <div style="color:#c9a14a;font-weight:600;letter-spacing:2px">PRECIO</div>
                    <h2 class="serif">${{ number_format($inmueble->valor, 0, ',', '.') }}</h2>
                    @if($inmueble->administracion)<small class="text-muted">+ Admin ${{ number_format($inmueble->administracion,0,',','.') }}</small>@endif
                </div>

                <div class="d-grid gap-2 mb-3">
                    <a href="{{ route('credito.form', $inmueble->id_inmueble) }}" class="btn btn-gold"><i class="fas fa-money-check-dollar"></i> Solicitar Crédito</a>
                    <a href="{{ $wa }}" target="_blank" class="btn btn-success"><i class="fab fa-whatsapp"></i> Contactar por WhatsApp</a>
                    <button class="btn btn-outline-gold" data-bs-toggle="collapse" data-bs-target="#formInfo"><i class="fas fa-envelope"></i> Solicitar Información</button>
                </div>

                <div class="collapse" id="formInfo">
                    <hr>
                    <h5 class="serif">Solicitar información</h5>
                    @if(session('success'))<div class="alert alert-success">{{ session('success') }}</div>@endif
                    <form method="POST" action="{{ route('informacion.store') }}">@csrf
                        <input type="hidden" name="id_inmueble" value="{{ $inmueble->id_inmueble }}">
                        <div class="mb-2"><input class="form-control" name="nombre" placeholder="Nombre" required></div>
                        <div class="mb-2"><input class="form-control" type="email" name="correo" placeholder="Correo" required></div>
                        <div class="mb-2"><input class="form-control" name="telefono" placeholder="Teléfono" required></div>
                        <div class="mb-2"><textarea class="form-control" name="mensaje" rows="3" placeholder="Mensaje"></textarea></div>
                        <button class="btn btn-gold w-100"><i class="fas fa-paper-plane"></i> Enviar</button>
                    </form>
                </div>

                <hr>
                <h6 class="serif">Solicitud de visita / compra</h6>
                <form method="POST" action="{{ route('solicitud.store') }}">@csrf
                    <input type="hidden" name="id_inmueble" value="{{ $inmueble->id_inmueble }}">
                    <input type="hidden" name="tipo_solicitud" value="{{ strtolower($inmueble->negocio->nombre_tipo ?? 'compra')==='arriendo'?'arriendo':'compra' }}">
                    <div class="mb-2"><input class="form-control" name="p_nombre" placeholder="Nombre" required></div>
                    <div class="mb-2"><input class="form-control" name="p_apellido" placeholder="Apellido" required></div>
                    <div class="mb-2"><input class="form-control" name="documento" placeholder="Documento" required></div>
                    <div class="mb-2"><input class="form-control" name="celular" placeholder="Celular" required></div>
                    <div class="mb-2"><input class="form-control" type="email" name="correo" placeholder="Correo" required></div>
                    <div class="mb-2"><textarea class="form-control" name="mensaje" placeholder="Mensaje" rows="2"></textarea></div>
                    <button class="btn btn-outline-gold w-100"><i class="fas fa-paper-plane"></i> Enviar</button>
                </form>
            </div>
        </div>
    </div>

    @if($similares->count())
    <div class="mt-5">
        <h3 class="serif text-center mb-4">Propiedades similares</h3>
        <div class="row g-4">
            @foreach($similares as $inmueble)
                <div class="col-md-4">@include('components.property-card', ['inmueble' => $inmueble])</div>
            @endforeach
        </div>
    </div>
    @endif
</div>

@if($gmapsKey && $inmueble->latitud && $inmueble->longitud)
@push('scripts')
<script>
    window.__prop = { lat: {{ $inmueble->latitud }}, lng: {{ $inmueble->longitud }}, titulo: @json($inmueble->titulo) };
    function initPropMap() {
        const p = window.__prop;
        const map = new google.maps.Map(document.getElementById('propMap'), {
            center: { lat: p.lat, lng: p.lng }, zoom: 15,
            styles: [{elementType:'geometry',stylers:[{color:'#1d1d1d'}]},{elementType:'labels.text.stroke',stylers:[{color:'#1d1d1d'}]},{elementType:'labels.text.fill',stylers:[{color:'#c9a14a'}]}]
        });
        new google.maps.Marker({ position: { lat: p.lat, lng: p.lng }, map, title: p.titulo });
        fetch('{{ route("api.cercanas") }}?lat='+p.lat+'&lng='+p.lng+'&radio=3')
            .then(r=>r.json()).then(d=>{
                const list = document.getElementById('cercanasList');
                if (!d.items || !d.items.length) { list.innerHTML = 'No hay propiedades cercanas.'; return; }
                list.innerHTML = d.items.slice(0,5).map(i=>`<div>• <a href="/propiedad/${i.id_inmueble}" class="text-gold">${i.titulo}</a> — ${(+i.distancia_km).toFixed(2)} km</div>`).join('');
                d.items.forEach(i => {
                    if (i.id_inmueble == {{ $inmueble->id_inmueble }}) return;
                    new google.maps.Marker({
                        position: { lat: +i.latitud, lng: +i.longitud }, map, title: i.titulo,
                        icon: { path: google.maps.SymbolPath.CIRCLE, scale: 7, fillColor: '#c9a14a', fillOpacity: 0.9, strokeColor: '#000', strokeWeight: 1 }
                    });
                });
            });
    }
</script>
<script async src="https://maps.googleapis.com/maps/api/js?key={{ $gmapsKey }}&callback=initPropMap"></script>
@endpush
@endif
@endsection
