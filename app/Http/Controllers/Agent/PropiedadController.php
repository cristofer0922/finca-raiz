<?php
namespace App\Http\Controllers\Agent;

use App\Http\Controllers\Controller;
use App\Models\Inmueble;
use Illuminate\Http\Request;

class PropiedadController extends Controller
{
    public function index(Request $r)
    {
        $q = Inmueble::with('imagenes','tipo','negocio');
        if ($r->filled('estado')) $q->where('estado_propiedad', $r->estado);
        if ($r->filled('ciudad')) $q->where('ciudad', 'like', "%{$r->ciudad}%");
        $inmuebles = $q->orderByDesc('fecha_publicacion')->paginate(12)->withQueryString();
        return view('agent.propiedades.index', compact('inmuebles'));
    }
}
