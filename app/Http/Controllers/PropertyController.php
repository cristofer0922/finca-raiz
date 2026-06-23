<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Inmueble;
use App\Models\TipoInmueble;
use App\Models\TipoNegocio;
use App\Models\VisitaPropiedad;

class PropertyController extends Controller
{
    public function index(Request $r)
    {
        $q = Inmueble::with('imagenes','tipo','negocio')->publicas();

        if ($r->filled('ciudad'))     $q->where('ciudad', 'like', "%{$r->ciudad}%");
        if ($r->filled('tipo'))       $q->where('id_tipo_inmueble', $r->tipo);
        if ($r->filled('negocio'))    $q->where('id_tipo_negocio', $r->negocio);
        if ($r->filled('min'))        $q->where('valor', '>=', $r->min);
        if ($r->filled('max'))        $q->where('valor', '<=', $r->max);
        if ($r->filled('habitaciones')) $q->where('habitaciones', '>=', $r->habitaciones);
        if ($r->filled('buscar'))     $q->where('titulo', 'like', "%{$r->buscar}%");

        $inmuebles = $q->orderByDesc('fecha_publicacion')->paginate(9)->withQueryString();
        $tipos = TipoInmueble::all();
        $negocios = TipoNegocio::all();

        return view('pages.propiedades', compact('inmuebles','tipos','negocios'));
    }

    public function show($id)
    {
        $inmueble = Inmueble::with('imagenes','imagenesHd','tipo','negocio','asesor')->findOrFail($id);

        // Conteo de visitas
        $inmueble->increment('visitas');
        VisitaPropiedad::create([
            'id_inmueble' => $inmueble->id_inmueble,
            'ip' => request()->ip(),
            'user_agent' => substr(request()->userAgent() ?? '', 0, 250),
        ]);

        $similares = Inmueble::with('imagenes')
            ->where('id_tipo_inmueble', $inmueble->id_tipo_inmueble)
            ->where('id_inmueble','!=',$id)
            ->publicas()
            ->limit(3)->get();

        $bancos = \App\Http\Controllers\CreditoController::BANCOS;
        return view('pages.propiedad', compact('inmueble','similares','bancos'));
    }

    public function comprar(Request $r)
    {
        // 6 iniciales — botón "Ver más" carga hasta 30
        $inmuebles = Inmueble::with('imagenes','tipo')
            ->publicas()
            ->whereHas('negocio', fn($q)=>$q->where('nombre_tipo','Venta'))
            ->orderByDesc('fecha_publicacion')
            ->paginate(6);
        return view('pages.comprar', compact('inmuebles'));
    }

    public function arrendar(Request $r)
    {
        $inmuebles = Inmueble::with('imagenes','tipo')
            ->publicas()
            ->whereHas('negocio', fn($q)=>$q->where('nombre_tipo','Arriendo'))
            ->orderByDesc('fecha_publicacion')
            ->paginate(6);
        return view('pages.arrendar', compact('inmuebles'));
    }

    // AJAX: "Ver más" — devuelve hasta 30 propiedades por categoría
    public function verMas(Request $r)
    {
        $categoria = $r->get('categoria','venta'); // venta | arriendo
        $q = Inmueble::with('imagenes','tipo')->publicas()
            ->whereHas('negocio', fn($qq)=>$qq->where('nombre_tipo', $categoria === 'arriendo' ? 'Arriendo' : 'Venta'))
            ->orderByDesc('fecha_publicacion')
            ->limit(30);
        return response()->json($q->get());
    }

    public function apiList(Request $r)
    {
        $q = Inmueble::with('imagenes','tipo','negocio')->publicas();
        if ($r->filled('buscar')) $q->where('titulo','like',"%{$r->buscar}%");
        return response()->json($q->limit(20)->get());
    }
}
