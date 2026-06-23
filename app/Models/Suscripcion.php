<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Suscripcion extends Model
{
    protected $table = 'suscripciones';
    protected $primaryKey = 'id_suscripcion';
    public $timestamps = false;
    protected $fillable = ['id_usuario','id_plan','proveedor','referencia','estado','inicio','fin'];

    public function plan() { return $this->belongsTo(Plan::class, 'id_plan'); }
    public function usuario() { return $this->belongsTo(User::class, 'id_usuario'); }
}
