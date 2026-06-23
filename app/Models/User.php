<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    protected $table = 'usuarios';
    protected $primaryKey = 'id_usuario';
    public $timestamps = false;

    protected $fillable = ['usuario','correo','contrasena','id_tipo_usuario','estado','token_recuperacion','ultimo_acceso'];
    protected $hidden = ['contrasena'];

    public function tipo() { return $this->belongsTo(TipoUsuario::class, 'id_tipo_usuario'); }
    public function cliente() { return $this->hasOne(Cliente::class, 'id_usuario'); }
}
