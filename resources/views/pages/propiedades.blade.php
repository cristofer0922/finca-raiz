@extends('layouts.app')
@section('title', 'Propiedades')
@section('content')
<section style="padding-top:140px;padding-bottom:60px" class="bg-dark text-white">
    <div class="container">
        <h1 class="serif text-center" data-aos="fade-up">Nuestras <span class="gold">Propiedades</span></h1>
        <p class="text-center text-muted" data-aos="fade-up" data-aos-delay="100">Explora nuestra colección curada</p>
    </div>
</section>

<div class="container my-5">
    <div class="admin-card">
        <form method="GET" class="row g-3">
            <div class="col-md-3"><input class="form-control" type="text" name="buscar" placeholder="Buscar título..." value="{{ request('buscar') }}"></div>
            <div class="col-md-2"><input class="form-control" type="text" name="ciudad" placeholder="Ciudad" value="{{ request('ciudad') }}"></div>
            <div class="col-md-2"><select class="form-select" name="tipo"><option value="">Tipo</option>
                @foreach($tipos as $t)<option value="{{$t->id_tipo_inmueble}}" @selected(request('tipo')==$t->id_tipo_inmueble)>{{$t->nombre_tipo}}</option>@endforeach
            </select></div>
            <div class="col-md-2"><select class="form-select" name="negocio"><option value="">Negocio</option>
                @foreach($negocios as $n)<option value="{{$n->id_tipo_negocio}}" @selected(request('negocio')==$n->id_tipo_negocio)>{{$n->nombre_tipo}}</option>@endforeach
            </select></div>
            <div class="col-md-1"><input class="form-control" type="number" name="min" placeholder="Mín" value="{{ request('min') }}"></div>
            <div class="col-md-1"><input class="form-control" type="number" name="max" placeholder="Máx" value="{{ request('max') }}"></div>
            <div class="col-md-1"><button class="btn btn-gold w-100"><i class="fas fa-filter"></i></button></div>
        </form>
    </div>

    <input type="text" id="buscador-live" class="form-control mb-4" placeholder="🔍 Filtrar en pantalla (búsqueda en vivo)">

    <div class="row g-4">
        @forelse($inmuebles as $inmueble)
            <div class="col-lg-4 col-md-6" data-aos="fade-up">@include('components.property-card', ['inmueble' => $inmueble])</div>
        @empty
            <div class="col-12 text-center py-5"><h4>No encontramos propiedades con esos filtros</h4></div>
        @endforelse
    </div>

    <div class="mt-5 d-flex justify-content-center">{{ $inmuebles->links('pagination::bootstrap-5') }}</div>
</div>
@endsection
