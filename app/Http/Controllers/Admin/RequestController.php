<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Solicitud;

class RequestController extends Controller
{
    public function index(Request $r)
    {
        $q = Solicitud::with('cliente','inmueble');
        if ($r->filled('estado')) $q->where('estado', $r->estado);
        if ($r->filled('tipo')) $q->where('tipo_solicitud', $r->tipo);
        if ($r->filled('buscar')) {
            $q->whereHas('cliente', fn($x)=>$x->where('p_nombre','like',"%{$r->buscar}%")
                ->orWhere('correo','like',"%{$r->buscar}%"));
        }
        $solicitudes = $q->orderByDesc('fecha')->paginate(15)->withQueryString();
        return view('admin.requests.index', compact('solicitudes'));
    }

    public function aprobar($id) { Solicitud::findOrFail($id)->update(['estado'=>'aprobada']); return back()->with('success','Aprobada'); }
    public function rechazar($id){ Solicitud::findOrFail($id)->update(['estado'=>'rechazada']); return back()->with('success','Rechazada'); }
    public function destroy($id) { Solicitud::findOrFail($id)->delete(); return back()->with('success','Eliminada'); }
}
