<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Transaccion extends Model {
    protected $table = 'transacciones';
    protected $primaryKey = 'id_transaccion';
    public $timestamps = false;
    protected $fillable = ['id_inmueble','id_credito','monto','tipo','referencia','estado','fecha'];
}
