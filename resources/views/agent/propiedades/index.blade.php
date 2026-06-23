@extends('layouts.admin')
@section('page','Propiedades')
@section('content')
<div class="admin-card">
    <form method="GET" class="row g-2 mb-3">
        <div class="col-md-3">
            <select name="estado" class="form-control">
                <option value="">Todos</option>
                <option value="Disponible" @selected(request('estado')==='Disponible')>Disponible</option>
                <option value="Vendida"    @selected(request('estado')==='Vendida')>Vendida</option>
                <option value="Reservada"  @selected(request('estado')==='Reservada')>Reservada</option>
                <option value="Arrendada"  @selected(request('estado')==='Arrendada')>Arrendada</option>
            </select>
        </div>
        <div class="col-md-3"><input name="ciudad" value="{{ request('ciudad') }}" class="form-control" placeholder="Ciudad"></div>
        <div class="col-md-2"><button class="btn btn-gold w-100">Filtrar</button></div>
    </form>
    <table class="table-admin">
        <thead><tr><th>#</th><th>Título</th><th>Ciudad</th><th>Valor</th><th>Estado</th><th>Visitas</th></tr></thead>
        <tbody>
        @foreach($inmuebles as $i)
        <tr>
            <td>{{ $i->id_inmueble }}</td>
            <td>{{ $i->titulo }}</td>
            <td>{{ $i->ciudad }}</td>
            <td>${{ number_format($i->valor,0,',','.') }}</td>
            <td><span class="badge-est {{ strtolower($i->estado_propiedad) }}">{{ $i->estado_propiedad }}</span></td>
            <td>{{ $i->visitas }}</td>
        </tr>
        @endforeach
        </tbody>
    </table>
    {{ $inmuebles->links() }}
</div>
@endsection
