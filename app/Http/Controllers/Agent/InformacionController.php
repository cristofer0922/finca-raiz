<?php
namespace App\Http\Controllers\Agent;

use App\Http\Controllers\Controller;
use App\Models\SolicitudInformacion;
use Illuminate\Http\Request;

class InformacionController extends Controller
{
    public function index(Request $r)
    {
        $q = SolicitudInformacion::with('inmueble');
        if ($r->filled('estado')) $q->where('estado', $r->estado);
        $solicitudes = $q->orderByDesc('fecha')->paginate(15)->withQueryString();
        return view('agent.informacion.index', compact('solicitudes'));
    }

    public function atender($id)
    {
        $s = SolicitudInformacion::findOrFail($id);
        $s->estado = 'Atendida';
        $s->save();
        return back()->with('success', 'Solicitud marcada como atendida.');
    }

    public function cerrar($id)
    {
        $s = SolicitudInformacion::findOrFail($id);
        $s->estado = 'Cerrada';
        $s->save();
        return back()->with('success', 'Solicitud cerrada.');
    }
}
