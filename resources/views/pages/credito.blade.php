@extends('layouts.app')
@section('title', 'Solicitar crédito')
@section('content')
<section style="padding-top:140px" class="bg-dark text-white pb-4">
    <div class="container">
        <a href="{{ route('propiedades.show', $inmueble->id_inmueble) }}" class="text-gold text-decoration-none"><i class="fas fa-arrow-left"></i> Volver</a>
        <h1 class="serif mt-2">Solicitar crédito</h1>
        <p>{{ $inmueble->titulo }} — ${{ number_format($inmueble->valor,0,',','.') }}</p>
    </div>
</section>

<div class="container my-5">
    <div class="form-card mx-auto" style="max-width:780px">
        @if(session('success'))<div class="alert alert-success">{{ session('success') }}</div>@endif
        <form method="POST" action="{{ route('credito.store') }}">@csrf
            <input type="hidden" name="id_inmueble" value="{{ $inmueble->id_inmueble }}">
            <div class="row g-3">
                <div class="col-md-6"><label>Nombre completo</label><input class="form-control" name="nombre_completo" required></div>
                <div class="col-md-6"><label>Documento</label><input class="form-control" name="documento" required></div>
                <div class="col-md-6"><label>Correo</label><input class="form-control" type="email" name="correo" required></div>
                <div class="col-md-6"><label>Teléfono</label><input class="form-control" name="telefono" required></div>
                <div class="col-md-6"><label>Ingresos mensuales (COP)</label><input class="form-control" type="number" min="0" step="1" name="ingresos_mensuales" required></div>
                <div class="col-md-6"><label>Tipo de contrato</label>
                    <select class="form-control" name="tipo_contrato" required>
                        <option value="">Selecciona</option>
                        <option>Indefinido</option><option>Término fijo</option>
                        <option>Prestación de servicios</option><option>Independiente</option>
                    </select>
                </div>
                <div class="col-md-6"><label>Empresa</label><input class="form-control" name="empresa"></div>
                <div class="col-md-6"><label>Banco</label>
                    <select class="form-control" name="banco" required>
                        <option value="">Selecciona un banco</option>
                        @foreach($bancos as $b)<option value="{{ $b }}">{{ $b }}</option>@endforeach
                    </select>
                </div>
                <div class="col-md-6"><label>Valor de la propiedad</label><input class="form-control" type="number" min="0" name="valor_propiedad" value="{{ $inmueble->valor }}" required></div>
                <div class="col-md-6"><label>Cuota inicial</label><input class="form-control" type="number" min="0" name="cuota_inicial" required></div>
                <div class="col-12"><label>Comentarios</label><textarea class="form-control" name="comentarios" rows="3"></textarea></div>
            </div>
            <button class="btn btn-gold mt-3 w-100"><i class="fas fa-paper-plane"></i> Enviar solicitud de crédito</button>
        </form>
    </div>
</div>
@endsection
