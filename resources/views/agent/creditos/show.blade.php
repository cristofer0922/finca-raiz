@extends('layouts.admin')
@section('page','Detalle de crédito')
@section('content')
<div class="admin-card">
    <h4 class="serif">{{ $credito->nombre_completo }} — {{ $credito->banco }}</h4>
    <p><span class="badge-est {{ strtolower($credito->estado) }}">{{ $credito->estado }}</span> · {{ \Carbon\Carbon::parse($credito->fecha_solicitud)->format('d/m/Y H:i') }}</p>
    <div class="row">
        <div class="col-md-6">
            <p><strong>Documento:</strong> {{ $credito->documento }}</p>
            <p><strong>Correo:</strong> {{ $credito->correo }}</p>
            <p><strong>Teléfono:</strong> {{ $credito->telefono }}</p>
            <p><strong>Ingresos mensuales:</strong> ${{ number_format($credito->ingresos_mensuales,0,',','.') }}</p>
            <p><strong>Tipo contrato:</strong> {{ $credito->tipo_contrato }}</p>
            <p><strong>Empresa:</strong> {{ $credito->empresa }}</p>
        </div>
        <div class="col-md-6">
            <p><strong>Valor propiedad:</strong> ${{ number_format($credito->valor_propiedad,0,',','.') }}</p>
            <p><strong>Cuota inicial:</strong> ${{ number_format($credito->cuota_inicial,0,',','.') }}</p>
            <p><strong>Propiedad:</strong> {{ $credito->inmueble->titulo ?? '—' }}</p>
            <p><strong>Comentarios:</strong><br>{{ $credito->comentarios }}</p>
        </div>
    </div>
    @if($credito->estado === 'Pendiente')
        <form method="POST" action="{{ route('agente.creditos.aprobar',$credito->id_credito) }}" class="d-inline">@csrf<button class="btn btn-success">Aprobar</button></form>
        <form method="POST" action="{{ route('agente.creditos.rechazar',$credito->id_credito) }}" class="d-inline">@csrf<button class="btn btn-danger">Rechazar</button></form>
    @endif
    <a class="btn btn-outline-dark" href="{{ route('agente.creditos.index') }}">Volver</a>
</div>
@endsection
