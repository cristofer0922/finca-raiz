@extends('layouts.admin')
@section('page','Solicitudes de crédito')
@section('content')
<div class="admin-card">
    <form method="GET" class="row g-2 mb-3">
        <div class="col-md-3">
            <select name="estado" class="form-control">
                <option value="">Todos los estados</option>
                <option value="Pendiente"  @selected(request('estado')==='Pendiente')>Pendiente</option>
                <option value="Aprobado"   @selected(request('estado')==='Aprobado')>Aprobado</option>
                <option value="Rechazado"  @selected(request('estado')==='Rechazado')>Rechazado</option>
            </select>
        </div>
        <div class="col-md-3"><input name="banco" value="{{ request('banco') }}" class="form-control" placeholder="Banco"></div>
        <div class="col-md-2"><button class="btn btn-gold w-100">Filtrar</button></div>
        <div class="col-md-4 text-end">
            <a class="btn btn-outline-dark" target="_blank" href="{{ route('agente.creditos.pdf') }}"><i class="fas fa-file-pdf"></i> PDF</a>
            <a class="btn btn-outline-dark" href="{{ route('agente.creditos.excel') }}"><i class="fas fa-file-excel"></i> Excel</a>
        </div>
    </form>

    <table class="table-admin">
        <thead><tr>
            <th>#</th><th>Cliente</th><th>Banco</th><th>Valor</th><th>Cuota inicial</th><th>Estado</th><th>Fecha</th><th></th>
        </tr></thead>
        <tbody>
        @foreach($creditos as $c)
            <tr>
                <td>{{ $c->id_credito }}</td>
                <td>{{ $c->nombre_completo }}<br><small>{{ $c->correo }}</small></td>
                <td>{{ $c->banco }}</td>
                <td>${{ number_format($c->valor_propiedad,0,',','.') }}</td>
                <td>${{ number_format($c->cuota_inicial,0,',','.') }}</td>
                <td><span class="badge-est {{ strtolower($c->estado) }}">{{ $c->estado }}</span></td>
                <td>{{ \Carbon\Carbon::parse($c->fecha_solicitud)->format('d/m/Y') }}</td>
                <td>
                    <a class="btn btn-sm btn-outline-dark" href="{{ route('agente.creditos.show',$c->id_credito) }}">Ver</a>
                    @if($c->estado === 'Pendiente')
                        <form method="POST" action="{{ route('agente.creditos.aprobar',$c->id_credito) }}" class="d-inline">@csrf<button class="btn btn-sm btn-success">Aprobar</button></form>
                        <form method="POST" action="{{ route('agente.creditos.rechazar',$c->id_credito) }}" class="d-inline">@csrf<button class="btn btn-sm btn-danger">Rechazar</button></form>
                    @endif
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
    {{ $creditos->links() }}
</div>
@endsection
