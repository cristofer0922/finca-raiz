@extends('layouts.admin')
@section('page','Panel del Agente')
@section('content')
<div class="row g-4 mb-4">
    @php $cards = [
        ['Créditos solicitados', $stats['creditos_solicitados'], 'fa-file-invoice-dollar', '#0d6efd', 'creditos_solicitados'],
        ['Créditos aprobados',  $stats['creditos_aprobados'],  'fa-check-circle',          '#198754', 'creditos_aprobados'],
        ['Créditos rechazados', $stats['creditos_rechazados'], 'fa-times-circle',          '#dc3545', 'creditos_rechazados'],
        ['Casas vendidas',      $stats['casas_vendidas'],      'fa-handshake',             '#6f42c1', 'casas_vendidas'],
        ['Casas disponibles',   $stats['casas_disponibles'],   'fa-home',                  '#c9a14a', 'casas_disponibles'],
        ['Visitas',             $stats['visitas'],             'fa-eye',                   '#20c997', 'visitas'],
        ['Usuarios registrados',$stats['usuarios'],            'fa-users',                 '#0dcaf0', 'usuarios'],
        ['Solicitudes info',    $stats['solicitudes_info'],    'fa-envelope',              '#fd7e14', null],
    ]; @endphp
    @foreach($cards as $c)
        <div class="col-md-6 col-lg-3">
            <div class="kpi-card">
                <div>
                    <div class="label">{{ $c[0] }}</div>
                    <div class="value" @if($c[4]) data-live="{{ $c[4] }}" @endif>{{ $c[1] }}</div>
                </div>
                <div class="icon" style="background: {{ $c[3] }}"><i class="fas {{ $c[2] }}"></i></div>
            </div>
        </div>
    @endforeach
</div>

<div class="row g-4">
    <div class="col-lg-8"><div class="admin-card">
        <h5 class="serif">Créditos solicitados (últimos 14 días)</h5>
        <canvas id="chartCreditos" height="110"></canvas>
    </div></div>
    <div class="col-lg-4"><div class="admin-card">
        <h5 class="serif">Por banco</h5>
        <canvas id="chartBancos" height="110"></canvas>
    </div></div>
</div>

<div class="row g-4 mt-1">
    <div class="col-lg-6"><div class="admin-card">
        <h5 class="serif">Visitas por día</h5>
        <canvas id="chartVisitas" height="110"></canvas>
    </div></div>
    <div class="col-lg-6"><div class="admin-card">
        <h5 class="serif">Acciones rápidas</h5>
        <div class="d-flex flex-wrap gap-2 mt-2">
            <a href="{{ route('agente.creditos.index') }}" class="btn btn-gold"><i class="fas fa-list"></i> Ver créditos</a>
            <a href="{{ route('agente.info.index') }}" class="btn btn-outline-gold"><i class="fas fa-envelope"></i> Solicitudes información</a>
            <a href="{{ route('agente.creditos.pdf') }}" target="_blank" class="btn btn-outline-dark"><i class="fas fa-file-pdf"></i> Reporte PDF</a>
            <a href="{{ route('agente.creditos.excel') }}" class="btn btn-outline-dark"><i class="fas fa-file-excel"></i> Exportar Excel</a>
        </div>
    </div></div>
</div>

@push('scripts')
<script>
const c1 = new Chart(document.getElementById('chartCreditos'), {
    type:'line',
    data:{
        labels: @json($creditosPorDia->pluck('fecha')),
        datasets:[
            {label:'Solicitados', data:@json($creditosPorDia->pluck('total')), borderColor:'#0d6efd', tension:.3, fill:false},
            {label:'Aprobados',   data:@json($creditosPorDia->pluck('aprob')), borderColor:'#198754', tension:.3, fill:false},
        ]
    }
});
const c2 = new Chart(document.getElementById('chartBancos'), {
    type:'doughnut',
    data:{
        labels:@json($porBanco->pluck('banco')),
        datasets:[{data:@json($porBanco->pluck('total')), backgroundColor:['#c9a14a','#0d6efd','#198754','#dc3545','#6f42c1','#20c997','#fd7e14','#0dcaf0']}]
    }
});
const c3 = new Chart(document.getElementById('chartVisitas'), {
    type:'bar',
    data:{
        labels:@json($visitasPorDia->pluck('fecha')),
        datasets:[{label:'Visitas', data:@json($visitasPorDia->pluck('total')), backgroundColor:'#20c997'}]
    },
    options:{plugins:{legend:{display:false}}}
});

// Refresco en tiempo real cada 10s
async function refreshLive() {
    try {
        const r = await fetch('{{ route("agente.dashboard.live") }}', {headers:{'Accept':'application/json'}});
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
