<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class SolicitudInformacion extends Model {
    protected $table = 'solicitudes_informacion';
    protected $primaryKey = 'id_solicitud_info';
    public $timestamps = false;
    protected $fillable = ['id_inmueble','nombre','correo','telefono','mensaje','estado','fecha'];
    public function inmueble() { return $this->belongsTo(Inmueble::class, 'id_inmueble'); }
}
