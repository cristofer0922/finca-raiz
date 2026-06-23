<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class TipoCliente extends Model {
    protected $table='tipo_cliente'; protected $primaryKey='id_tipo_cliente'; public $timestamps=false;
    protected $fillable=['nombre_tipo'];
}
