<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Notificacion extends Model {
    protected $table = 'notificaciones';
    protected $primaryKey = 'id_notificacion';
    public $timestamps = false;
    protected $fillable = ['id_usuario','tipo','titulo','mensaje','url','leida','fecha'];
}
