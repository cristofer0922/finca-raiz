<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Inmueble extends Model {
    protected $table='inmuebles';
    protected $primaryKey='id_inmueble';
    public $timestamps=false;
    protected $fillable=[
        'titulo','id_tipo_inmueble','id_tipo_negocio','direccion','ciudad','barrio','estrato',
        'valor','administracion','area','habitaciones','banos','garajes','antiguedad',
        'descripcion','latitud','longitud','estado','id_asesor','video_url',
        // Nuevos
        'estado_propiedad','disponible','fecha_publicacion','fecha_venta',
        'visitas','destacado','tour_virtual'
    ];

    protected static function booted()
    {
        static::saving(function ($inm) {
            // Auto-geocodificación si falta lat/lng y hay API key
            if ((empty($inm->latitud) || empty($inm->longitud)) && $inm->direccion && config('services.google.maps_key')) {
                try {
                    $loc = app(\App\Services\GeocodingService::class)
                        ->geocode("{$inm->direccion}, {$inm->ciudad}, Colombia");
                    if ($loc) { $inm->latitud = $loc['lat']; $inm->longitud = $loc['lng']; }
                } catch (\Throwable $e) {}
            }
        });

        static::updating(function ($inm) {
            if ($inm->isDirty('estado_propiedad')) {
                $orig = $inm->getOriginal('estado_propiedad');
                $nuevo = $inm->estado_propiedad;
                HistorialEstado::create([
                    'id_inmueble' => $inm->id_inmueble,
                    'estado_anterior' => $orig,
                    'estado_nuevo' => $nuevo,
                    'id_usuario' => session('user.id'),
                ]);
                if ($nuevo === 'Vendida') {
                    $inm->disponible = false;
                    $inm->fecha_venta = now();
                    $inm->estado = 'vendido';
                } elseif ($nuevo === 'Arrendada') {
                    $inm->disponible = false;
                    $inm->estado = 'arrendado';
                } elseif ($nuevo === 'Reservada') {
                    $inm->disponible = false;
                    $inm->estado = 'reservado';
                } else {
                    $inm->disponible = true;
                    $inm->estado = 'disponible';
                }
            }
        });
    }

    public function tipo() { return $this->belongsTo(TipoInmueble::class, 'id_tipo_inmueble'); }
    public function negocio() { return $this->belongsTo(TipoNegocio::class, 'id_tipo_negocio'); }
    public function asesor() { return $this->belongsTo(User::class, 'id_asesor'); }
    public function imagenes() { return $this->hasMany(ImagenInmueble::class, 'id_inmueble'); }
    public function imagenesHd() { return $this->hasMany(ImagenPropiedad::class, 'id_inmueble')->orderBy('orden'); }
    public function solicitudes() { return $this->hasMany(Solicitud::class, 'id_inmueble'); }
    public function historial() { return $this->hasMany(HistorialEstado::class, 'id_inmueble'); }

    public function getImagenPrincipalAttribute() {
        $img = $this->imagenes()->first();
        return $img ? $img->url_imagen : 'https://images.unsplash.com/photo-1568605114967-8130f3a36994?w=800';
    }

    // Scope: oculta automáticamente del catálogo público las vendidas
    public function scopePublicas($q) {
        return $q->where('disponible', true)->whereIn('estado_propiedad', ['Disponible','Reservada']);
    }
}
