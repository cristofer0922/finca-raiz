@extends('layouts.admin')
@section('page','Solicitudes')
@section('content')
<div class="admin-card">
    <form method="GET" class="row g-2 mb-3">
        <div class="col-md-4"><input class="form-control" name="buscar" placeholder="Buscar cliente o correo..." value="{{ request('buscar') }}"></div>
        <div class="col-md-3"><select class="form-select" name="estado"><option value="">Todos los estados</option>
            @foreach(['pendiente','aprobada','rechazada'] as $e)<option @selected(request('estado')===$e)>{{$e}}</option>@endforeach
        </select></div>
        <div class="col-md-3"><select class="form-select" name="tipo"><option value="">Todos los tipos</option>
            @foreach(['compra','arriendo','visita'] as $t)<option @selected(request('tipo')===$t)>{{$t}}</option>@endforeach
        </select></div>
        <div class="col-md-2"><button class="btn btn-gold w-100"><i class="fas fa-filter"></i> Filtrar</button></div>
    </form>
    <table class="table-admin">
        <thead><tr><th>#</th><th>Cliente</th><th>Inmueble</th><th>Tipo</th><th>Mensaje</th><th>Estado</th><th>Fecha</th><th>Acciones</th></tr></thead>
        <tbody>
        @foreach($solicitudes as $s)
            <tr>
                <td>{{$s->id_solicitud}}</td>
                <td>{{$s->cliente->nombre_completo ?? '—'}}<br><small class="text-muted">{{$s->cliente->correo ?? ''}}</small></td>
                <td>{{ Str::limit($s->inmueble->titulo ?? '—', 30) }}</td>
                <td>{{ ucfirst($s->tipo_solicitud) }}</td>
                <td>{{ Str::limit($s->mensaje, 50) }}</td>
                <td><span class="badge-est {{ $s->estado }}">{{ $s->estado }}</span></td>
                <td>{{ \Carbon\Carbon::parse($s->fecha)->format('d/m/Y') }}</td>
                <td>
                    @if($s->estado==='pendiente')
                        <form method="POST" action="{{ route('admin.solicitudes.aprobar',$s->id_solicitud) }}" class="d-inline">@csrf<button class="btn btn-sm btn-success"><i class="fas fa-check"></i></button></form>
                        <form method="POST" action="{{ route('admin.solicitudes.rechazar',$s->id_solicitud) }}" class="d-inline">@csrf<button class="btn btn-sm btn-warning"><i class="fas fa-times"></i></button></form>
                    @endif
                    <form action="{{ route('admin.solicitudes.destroy',$s->id_solicitud) }}" method="POST" class="d-inline">@csrf @method('DELETE')
                        <button type="button" class="btn btn-sm btn-outline-danger btn-delete"><i class="fas fa-trash"></i></button>
                    </form>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
    <div class="mt-3">{{ $solicitudes->links('pagination::bootstrap-5') }}</div>
</div>
@endsection
