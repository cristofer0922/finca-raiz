<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    protected $table = 'settings';
    protected $primaryKey = 'clave';
    public $incrementing = false;
    protected $keyType = 'string';
    public $timestamps = false;
    protected $fillable = ['clave','valor'];

    public static function get(string $clave, ?string $default = null): ?string
    {
        try {
            $row = static::find($clave);
            return $row ? $row->valor : $default;
        } catch (\Throwable $e) { return $default; }
    }

    public static function set(string $clave, ?string $valor): void
    {
        static::updateOrCreate(['clave'=>$clave], ['valor'=>$valor]);
    }
}
