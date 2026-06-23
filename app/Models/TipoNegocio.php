<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class TipoNegocio extends Model {
    protected $table='tipo_negocio'; protected $primaryKey='id_tipo_negocio'; public $timestamps=false;
    protected $fillable=['nombre_tipo'];
}
