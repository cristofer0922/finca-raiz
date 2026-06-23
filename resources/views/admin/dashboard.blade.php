@extends('layouts.admin')
@section('page','Dashboard')
@section('content')
<div class="row g-4 mb-4">
    @php $cards = [
        ['Créditos solicitados', $stats['creditos_solicitados'], 'fa-file-invoice-dollar', 'linear-gradient(135deg,#0d6efd,#3d8bfd)', 'creditos_solicitados'],
        ['Créditos aprobados',  $stats['creditos_aprobados'],  'fa-check-circle',          'linear-gradient(135deg,#198754,#28a745)', 'creditos_aprobados'],
        ['Créditos rechazados', $stats['creditos_rechazados'], 'fa-times-circle',          'linear-gradient(135deg,#dc3545,#e74c3c)', 'creditos_rechazados'],
        ['Casas vendidas',      $stats['vendidos'],            'fa-handshake',             'linear-gradient(135deg,#6f42c1,#8e6cd6)', 'vendidos'],
        ['Casas disponibles',   $stats['disponibles'],         'fa-home',                  'linear-gradient(135deg,#c9a14a,#e6c878)', 'disponibles'],
        ['Visitas',             $stats['visitas'],             'fa-eye',                   'linear-gradient(135deg,#20c997,#26d4a0)', 'visitas'],
        ['Usuarios registrados',$stats['usuarios'],            'fa-users',                 'linear-gradient(135deg,#0dcaf0,#3dd5f3)', 'usuarios'],
        ['Solicitudes',         $stats['solicitudes'],         'fa-inbox',                 'linear-gradient(135deg,#1abc9c,#16a085)', null],
    ]; @endphp
    @foreach($cards as $c)
        <div class="col-md-6 col-lg-3"><div class="kpi-card">
            <div><div class="label">{{ $c[0] }}</div><div class="value" @if($c[4]) data-live="{{ $c[4] }}" @endif>{{ $c[1] }}</div></div>
            <div class="icon" style="background:{{ $c[3] }}"><i class="fas {{ $c[2] }}"></i></div>
        </div></div>
    @endforeach
</div>

<div class="row g-4">
    <div class="col-lg-7"><div class="admin-card"><h5 class="serif">Créditos (últimos 14 días)</h5><canvas id="chartCreditos" height="120"></canvas></div></div>
    <div class="col-lg-5"><div class="admin-card"><h5 class="serif">Estado de propiedades</h5><canvas id="chartEstados" height="120"></canvas></div></div>
</div>

<div class="row g-4 mt-1">
    <div class="col-lg-7"><div class="admin-card"><h5 class="serif">Top ciudades</h5><canvas id="chartCiudades" height="120"></canvas></div></div>
    <div class="col-lg-5"><div class="admin-card">
        <h5 class="serif">Últimas solicitudes</h5>
        <table class="table-admin">
            <thead><tr><th>Cliente</th><th>Tipo</th><th>Estado</th></tr></thead>
            <tbody>
            @foreach($ultimasSolicitudes as $s)
                <tr>
                    <td>{{ $s->cliente->nombre_completo ?? '—' }}</td>
                    <td>{{ ucfirst($s->tipo_solicitud) }}</td>
                    <td><span class="badge-est {{ $s->estado }}">{{ $s->estado }}</span></td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div></div>
</div>

@push('scripts')
<script>
new Chart(document.getElementById('chartCreditos'), {
    type: 'line',
    data: {
        labels: @json($creditosSerie->pluck('fecha')),
        datasets: [{label:'Créditos', data:@json($creditosSerie->pluck('total')), borderColor:'#0d6efd', tension:.3, fill:false}]
    }
});
new Chart(document.getElementById('chartEstados'), {
    type: 'doughnut',
    data: {
        labels: ['Disponibles','Vendidas','Arrendadas'],
        datasets: [{data: [{{$stats['disponibles']}},{{$stats['vendidos']}},{{$stats['arrendados']}}], backgroundColor:['#c9a14a','#dc3545','#3498db']}]
    },
});
new Chart(document.getElementById('chartCiudades'), {
    type: 'bar',
    data: {
        labels: @json($porCiudad->pluck('ciudad')),
        datasets: [{label:'Propiedades', data:@json($porCiudad->pluck('total')), backgroundColor:'#c9a14a'}]
    },
    options:{plugins:{legend:{display:false}}}
});
// Refresco en tiempo real
async function refreshLive() {
    try {
        const r = await fetch('{{ route("admin.dashboard.live") }}', {headers:{'Accept':'application/json'}});
        const j = await r.json();
        document.querySelectorAll('[data-live]').forEach(el => {
            const k = el.dataset.live;
            if (j[k] !== undefined) el.textContent = j[k];
        });
    } catch(e){}
}
setInterval(refreshLive, 10000);
</script>
@endpush
@endsection
