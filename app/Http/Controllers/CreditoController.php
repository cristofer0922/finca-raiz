<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Credito;
use App\Models\Notificacion;
use App\Models\Inmueble;

class CreditoController extends Controller
{
    public const BANCOS = [
        'Bancolombia','Davivienda','BBVA','Banco de Bogotá',
        'Banco de Occidente','Scotiabank Colpatria','Banco Popular','Itaú'
    ];

    public function form($id)
    {
        $inmueble = Inmueble::findOrFail($id);
        $bancos = self::BANCOS;
        return view('pages.credito', compact('inmueble','bancos'));
    }

    public function store(Request $r)
    {
        $data = $r->validate([
            'id_inmueble'        => 'nullable|integer|exists:inmuebles,id_inmueble',
            'nombre_completo'    => 'required|string|max:150',
            'documento'          => 'required|string|max:30',
            'correo'             => 'required|email|max:120',
            'telefono'           => 'required|string|max:30',
            'ingresos_mensuales' => 'required|numeric|min:0',
            'tipo_contrato'      => 'required|string|max:80',
            'empresa'            => 'nullable|string|max:150',
            'banco'              => 'required|in:'.implode(',', self::BANCOS),
            'valor_propiedad'    => 'required|numeric|min:0',
            'cuota_inicial'      => 'required|numeric|min:0',
            'comentarios'        => 'nullable|string|max:2000',
        ]);

        $data['estado'] = 'Pendiente';
        $data['fecha_solicitud'] = now();
        $credito = Credito::create($data);

        Notificacion::create([
            'tipo' => 'credito',
            'titulo' => 'Nueva solicitud de crédito',
            'mensaje' => "Cliente {$credito->nombre_completo} solicitó crédito con {$credito->banco}.",
            'url' => '/agente/creditos',
        ]);

        if ($r->wantsJson()) {
            return response()->json(['ok'=>true,'message'=>'Solicitud de crédito enviada.']);
        }
        return back()->with('success', '¡Tu solicitud de crédito fue enviada! Un asesor te contactará pronto.');
    }
}
