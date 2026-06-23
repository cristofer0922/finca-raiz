@extends('layouts.app')
@section('title','Configuración')
@section('content')
<section style="padding-top:140px" class="bg-dark text-white pb-5">
    <div class="container">
        <h1 class="serif">Configuración de integraciones</h1>
        <p class="text-muted">WhatsApp, claves y mensajes</p>

        @if(session('success'))<div class="alert alert-success">{{ session('success') }}</div>@endif

        <form method="POST" action="{{ route('admin.settings.update') }}" class="form-card mt-3">
            @csrf @method('PUT')
            <div class="mb-3">
                <label class="form-label">Número WhatsApp (con código país, sin +)</label>
                <input class="form-control" name="whatsapp_number" value="{{ $settings['whatsapp_number'] }}" placeholder="573001234567" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Mensaje predeterminado</label>
                <textarea class="form-control" name="whatsapp_mensaje" rows="3">{{ $settings['whatsapp_mensaje'] }}</textarea>
            </div>
            <button class="btn btn-gold"><i class="fas fa-save"></i> Guardar</button>
        </form>

        <div class="admin-card mt-4">
            <h5 class="serif">Variables .env activas</h5>
            <ul class="text-muted">
                <li>OPENAI_API_KEY: {{ config('services.openai.key') ? '✓ configurada' : '✗ falta' }}</li>
                <li>GOOGLE_MAPS_API_KEY: {{ config('services.google.maps_key') ? '✓ configurada' : '✗ falta' }}</li>
                <li>MP_ACCESS_TOKEN: {{ config('services.mercadopago.token') ? '✓ configurada' : '✗ falta' }}</li>
                <li>STRIPE_SECRET: {{ config('services.stripe.secret') ? '✓ configurada' : '✗ falta' }}</li>
                <li>STRIPE_WEBHOOK_SECRET: {{ config('services.stripe.webhook_secret') ? '✓ configurada' : '✗ falta' }}</li>
            </ul>
        </div>
    </div>
</section>
@endsection
