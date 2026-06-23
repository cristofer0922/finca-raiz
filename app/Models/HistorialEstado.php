<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class HistorialEstado extends Model {
    protected $table = 'historial_estados';
    protected $primaryKey = 'id_historial';
    public $timestamps = false;
    protected $fillable = ['id_inmueble','estado_anterior','estado_nuevo','id_usuario','fecha'];
}
