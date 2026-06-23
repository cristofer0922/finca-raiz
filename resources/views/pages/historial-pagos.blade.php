@extends('layouts.app')
@section('title','Historial de pagos')
@section('content')
<section style="padding-top:140px" class="bg-dark text-white pb-5"><div class="container">
<h1 class="serif">Historial de pagos</h1>

<div class="admin-card mt-4">
<h4 class="serif">Transacciones</h4>
<table class="table table-dark table-striped"><thead><tr><th>ID</th><th>Tipo</th><th>Proveedor</th><th>Monto</th><th>Estado</th><th>Fecha</th></tr></thead><tbody>
@forelse($trx as $t)
<tr><td>{{ $t->id_transaccion }}</td><td>{{ $t->tipo }}</td><td>{{ $t->proveedor }}</td><td>${{ number_format($t->monto,2) }}</td><td>{{ $t->estado }}</td><td>{{ $t->fecha }}</td></tr>
@empty<tr><td colspan="6" class="text-center text-muted">Sin transacciones</td></tr>@endforelse
</tbody></table>
</div>

<div class="admin-card mt-4">
<h4 class="serif">Suscripciones</h4>
<table class="table table-dark table-striped"><thead><tr><th>Plan</th><th>Proveedor</th><th>Estado</th><th>Inicio</th><th>Fin</th></tr></thead><tbody>
@forelse($subs as $s)
<tr><td>{{ $s->plan->nombre ?? '—' }}</td><td>{{ $s->proveedor }}</td><td>{{ $s->estado }}</td><td>{{ $s->inicio }}</td><td>{{ $s->fin }}</td></tr>
@empty<tr><td colspan="5" class="text-center text-muted">Sin suscripciones</td></tr>@endforelse
</tbody></table>
</div>
</div></section>
@endsection
