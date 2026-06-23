<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class TipoInmueble extends Model {
    protected $table='tipo_inmueble'; protected $primaryKey='id_tipo_inmueble'; public $timestamps=false;
    protected $fillable=['nombre_tipo'];
}
