@extends('layouts.app')
@section('title','Planes Premium')
@section('content')
<section style="padding-top:140px" class="bg-dark text-white pb-5">
    <div class="container">
        <h1 class="serif text-center">Planes Premium</h1>
        <p class="text-center text-muted">Destaca propiedades o conviértete en agente premium</p>
        <div class="row g-4 mt-4">
        @foreach($planes as $plan)
            <div class="col-md-6">
                <div class="form-card text-center">
                    <h3 class="serif">{{ $plan->nombre }}</h3>
                    <p class="text-muted">{{ $plan->descripcion }}</p>
                    <h2 class="text-gold">{{ $plan->moneda }} {{ number_format($plan->precio, 2) }}</h2>
                    <small class="text-muted">Duración: {{ $plan->duracion_dias }} días</small>

                    @if(strtolower($plan->moneda)==='cop')
                        <form method="POST" action="{{ route('pagos.mp.destacar') }}" class="mt-3">
                            @csrf
                            <input type="hidden" name="id_plan" value="{{ $plan->id_plan }}">
                            <input class="form-control mb-2" name="id_inmueble" placeholder="ID Inmueble a destacar" required>
                            <button class="btn btn-gold w-100"><i class="fab fa-cc-mastercard"></i> Pagar con MercadoPago</button>
                        </form>
                    @else
                        <form method="POST" action="{{ route('pagos.stripe.suscribir') }}" class="mt-3">
                            @csrf
                            <input type="hidden" name="id_plan" value="{{ $plan->id_plan }}">
                            <button class="btn btn-gold w-100"><i class="fab fa-stripe-s"></i> Suscribirme con Stripe</button>
                        </form>
                    @endif
                </div>
            </div>
        @endforeach
        </div>
    </div>
</section>
@endsection
