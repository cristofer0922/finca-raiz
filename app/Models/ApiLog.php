<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class ApiLog extends Model {
    protected $table = 'api_logs';
    protected $primaryKey = 'id_log';
    public $timestamps = false;
    protected $fillable = ['endpoint','metodo','status','ip','payload','fecha'];
}
