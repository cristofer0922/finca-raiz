<div class="property-card property-card-wrap">
    <div class="property-img">
        <img src="{{ $inmueble->imagen_principal }}" alt="{{ $inmueble->titulo }}" loading="lazy">
        <span class="property-badge">{{ $inmueble->negocio->nombre_tipo ?? 'Venta' }}</span>
        <button class="property-fav" data-id="{{ $inmueble->id_inmueble }}"><i class="fas fa-heart"></i></button>
    </div>
    <div class="property-body">
        <div class="property-price">${{ number_format($inmueble->valor, 0, ',', '.') }}</div>
        <div class="property-title">{{ $inmueble->titulo }}</div>
        <div class="property-location"><i class="fas fa-map-marker-alt text-gold"></i> {{ $inmueble->barrio }}, {{ $inmueble->ciudad }}</div>
        <div class="property-meta">
            <span><i class="fas fa-bed"></i> {{ $inmueble->habitaciones }}</span>
            <span><i class="fas fa-bath"></i> {{ $inmueble->banos }}</span>
            <span><i class="fas fa-car"></i> {{ $inmueble->garajes }}</span>
            <span><i class="fas fa-ruler-combined"></i> {{ $inmueble->area }}m²</span>
        </div>
        <a href="{{ route('propiedades.show', $inmueble->id_inmueble) }}" class="btn btn-outline-gold btn-sm mt-3 w-100">Ver detalle</a>
    </div>
</div>
