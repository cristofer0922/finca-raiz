<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Solicitud extends Model {
    protected $table='solicitudes';
    protected $primaryKey='id_solicitud';
    public $timestamps=false;
    protected $fillable=['id_cliente','id_inmueble','tipo_solicitud','mensaje','estado','fecha'];

    public function cliente() { return $this->belongsTo(Cliente::class, 'id_cliente'); }
    public function inmueble() { return $this->belongsTo(Inmueble::class, 'id_inmueble'); }
}
