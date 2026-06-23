<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Plan extends Model
{
    protected $table = 'planes';
    protected $primaryKey = 'id_plan';
    public $timestamps = false;
    protected $fillable = ['nombre','descripcion','precio','moneda','duracion_dias','stripe_price_id','activo'];
}
