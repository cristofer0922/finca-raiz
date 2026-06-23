<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Cliente extends Model {
    protected $table='clientes';
    protected $primaryKey='id_cliente';
    public $timestamps=false;
    protected $fillable=['p_nombre','s_nombre','p_apellido','s_apellido','celular','correo','direccion','ciudad','documento','fecha_nacimiento','id_usuario','id_tipo_cliente'];

    public function usuario() { return $this->belongsTo(User::class, 'id_usuario'); }
    public function tipo() { return $this->belongsTo(TipoCliente::class, 'id_tipo_cliente'); }
    public function solicitudes() { return $this->hasMany(Solicitud::class, 'id_cliente'); }

    public function getNombreCompletoAttribute() {
        return trim("{$this->p_nombre} {$this->s_nombre} {$this->p_apellido} {$this->s_apellido}");
    }
}
