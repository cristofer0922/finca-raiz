@extends('layouts.admin')
@section('page','Solicitudes de información')
@section('content')
<div class="admin-card">
    <table class="table-admin">
        <thead><tr><th>#</th><th>Nombre</th><th>Correo</th><th>Teléfono</th><th>Propiedad</th><th>Mensaje</th><th>Estado</th><th>Fecha</th><th></th></tr></thead>
        <tbody>
        @foreach($solicitudes as $s)
            <tr>
                <td>{{ $s->id_solicitud_info }}</td>
                <td>{{ $s->nombre }}</td>
                <td>{{ $s->correo }}</td>
                <td>{{ $s->telefono }}</td>
                <td>{{ $s->inmueble->titulo ?? '—' }}</td>
                <td>{{ \Illuminate\Support\Str::limit($s->mensaje, 60) }}</td>
                <td><span class="badge-est {{ strtolower($s->estado) }}">{{ $s->estado }}</span></td>
                <td>{{ \Carbon\Carbon::parse($s->fecha)->format('d/m/Y') }}</td>
                <td>
                    @if($s->estado === 'Pendiente')
                        <form method="POST" action="{{ route('agente.info.atender',$s->id_solicitud_info) }}" class="d-inline">@csrf<button class="btn btn-sm btn-success">Atender</button></form>
                    @endif
                    @if($s->estado !== 'Cerrada')
                        <form method="POST" action="{{ route('agente.info.cerrar',$s->id_solicitud_info) }}" class="d-inline">@csrf<button class="btn btn-sm btn-outline-dark">Cerrar</button></form>
                    @endif
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
    {{ $solicitudes->links() }}
</div>
@endsection
