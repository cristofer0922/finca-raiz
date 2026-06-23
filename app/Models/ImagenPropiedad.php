<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class ImagenPropiedad extends Model {
    protected $table = 'imagenes_propiedad';
    protected $primaryKey = 'id_img_p';
    public $timestamps = false;
    protected $fillable = ['id_inmueble','url','titulo','orden','principal'];
}
