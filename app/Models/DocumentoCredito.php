<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class DocumentoCredito extends Model {
    protected $table = 'documentos_credito';
    protected $primaryKey = 'id_documento';
    public $timestamps = false;
    protected $fillable = ['id_credito','nombre','url','fecha'];
}
