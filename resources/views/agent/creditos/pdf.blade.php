<!doctype html>
<html><head><meta charset="utf-8"><title>Reporte de créditos</title>
<style>
body{font-family:Arial,sans-serif;padding:20px;color:#222}
h1{color:#c9a14a}
table{width:100%;border-collapse:collapse;margin-top:12px;font-size:12px}
th,td{border:1px solid #ddd;padding:6px;text-align:left}
th{background:#222;color:#c9a14a}
.print{margin:10px 0}
@media print{.print{display:none}}
</style></head>
<body>
<div class="print"><button onclick="window.print()">Imprimir / Guardar como PDF</button></div>
<h1>Reporte de Créditos — FincaRaíz</h1>
<p>Generado: {{ now()->format('d/m/Y H:i') }} · Total: {{ $creditos->count() }}</p>
<table>
<thead><tr><th>#</th><th>Cliente</th><th>Banco</th><th>Valor</th><th>Cuota inicial</th><th>Estado</th><th>Fecha</th></tr></thead>
<tbody>
@foreach($creditos as $c)
<tr>
    <td>{{ $c->id_credito }}</td>
    <td>{{ $c->nombre_completo }}<br><small>{{ $c->correo }}</small></td>
    <td>{{ $c->banco }}</td>
    <td>${{ number_format($c->valor_propiedad,0,',','.') }}</td>
    <td>${{ number_format($c->cuota_inicial,0,',','.') }}</td>
    <td>{{ $c->estado }}</td>
    <td>{{ \Carbon\Carbon::parse($c->fecha_solicitud)->format('d/m/Y') }}</td>
</tr>
@endforeach
</tbody></table>
</body></html>
