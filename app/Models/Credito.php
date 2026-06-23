<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Credito extends Model {
    protected $table = 'creditos';
    protected $primaryKey = 'id_credito';
    public $timestamps = false;
    protected $fillable = [
        'id_inmueble','nombre_completo','documento','correo','telefono',
        'ingresos_mensuales','tipo_contrato','empresa','banco',
        'valor_propiedad','cuota_inicial','comentarios','estado',
        'id_agente','fecha_solicitud','fecha_decision'
    ];
    public function inmueble() { return $this->belongsTo(Inmueble::class, 'id_inmueble'); }
}
