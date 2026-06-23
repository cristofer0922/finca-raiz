<?php
namespace App\Services;

use Illuminate\Support\Facades\Http;
use App\Models\ApiLog;

class GeocodingService
{
    protected string $key;

    public function __construct()
    {
        $this->key = (string) config('services.google.maps_key');
    }

    public function enabled(): bool { return !empty($this->key); }

    /** Devuelve ['lat'=>..., 'lng'=>...] o null. */
    public function geocode(string $direccion): ?array
    {
        if (!$this->enabled()) return null;
        try {
            $res = Http::timeout(15)->get('https://maps.googleapis.com/maps/api/geocode/json', [
                'address' => $direccion,
                'key'     => $this->key,
            ]);
            ApiLog::create([
                'endpoint' => 'google/geocode',
                'metodo'   => 'GET',
                'status'   => $res->status(),
                'ip'       => request()->ip(),
                'payload'  => substr($direccion, 0, 200),
            ]);
            $loc = $res->json('results.0.geometry.location');
            if (!$loc) return null;
            return ['lat' => $loc['lat'], 'lng' => $loc['lng']];
        } catch (\Throwable $e) {
            return null;
        }
    }
}
