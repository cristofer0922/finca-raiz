<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class ImagenInmueble extends Model {
    protected $table='imagenes_inmueble'; protected $primaryKey='id_imagen'; public $timestamps=false;
    protected $fillable=['id_inmueble','url_imagen'];
    public function inmueble() { return $this->belongsTo(Inmueble::class, 'id_inmueble'); }
}
