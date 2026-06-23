<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SolicitudInformacion;
use App\Models\Notificacion;

class InformacionController extends Controller
{
    public function store(Request $r)
    {
        $data = $r->validate([
            'id_inmueble' => 'nullable|integer|exists:inmuebles,id_inmueble',
            'nombre'      => 'required|string|max:120',
            'correo'      => 'required|email|max:120',
            'telefono'    => 'required|string|max:30',
            'mensaje'     => 'nullable|string|max:2000',
        ]);
        $data['estado'] = 'Pendiente';
        $data['fecha'] = now();
        $solicitud = SolicitudInformacion::create($data);

        Notificacion::create([
            'tipo' => 'info',
            'titulo' => 'Nueva solicitud de información',
            'mensaje' => "Cliente {$solicitud->nombre} solicitó información.",
            'url' => '/agente/solicitudes-informacion',
        ]);

        if ($r->wantsJson()) {
            return response()->json(['ok'=>true,'message'=>'Solicitud enviada.']);
        }
        return back()->with('success', '¡Solicitud de información enviada! Te contactaremos pronto.');
    }
}
