<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class Favorito extends Model {
    protected $table='favoritos'; protected $primaryKey='id_favorito'; public $timestamps=false;
    protected $fillable=['id_cliente','id_inmueble','fecha'];
}
