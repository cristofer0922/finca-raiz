<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class VisitaPropiedad extends Model {
    protected $table = 'visitas_propiedades';
    protected $primaryKey = 'id_visita_p';
    public $timestamps = false;
    protected $fillable = ['id_inmueble','ip','user_agent','fecha'];
}
