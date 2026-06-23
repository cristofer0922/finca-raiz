<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Agente extends Model {
    protected $table = 'agentes';
    protected $primaryKey = 'id_agente';
    public $timestamps = false;
    protected $fillable = ['id_usuario','nombre','correo','telefono','zona','activo'];
    public function usuario() { return $this->belongsTo(User::class, 'id_usuario'); }
}
