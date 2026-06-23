<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class ChatMensaje extends Model {
    protected $table = 'chat_mensajes';
    protected $primaryKey = 'id_mensaje';
    public $timestamps = false;
    protected $fillable = ['sesion','rol','mensaje','fecha'];
}
