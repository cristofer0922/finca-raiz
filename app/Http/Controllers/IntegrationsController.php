<?php

namespace App\Http\Controllers;

// Clases que van a utilizar
use Illuminate\Http\Request;
use App\Models\Inmueble;
use App\Models\TipoInmueble;
use App\Models\TipoNegocio;
use App\Models\VisitaPropiedad;

class IntegrationsController extends Controller
{
    /**
     * Mostrar listado de propiedades con filtros
     */
    public function index(Request $r)
    {
        // Consulta inicial de inmuebles públicos
        // También carga imágenes, tipo y negocio
        $q = Inmueble::with('imagenes','tipo','negocio')->publicas();

        // Filtro por ciudad
        if ($r->filled('ciudad')) {
            $q->where('ciudad', 'like', "%{$r->ciudad}%");
        }

        // Filtro por tipo de inmueble
        if ($r->filled('tipo')) {
            $q->where('id_tipo_inmueble', $r->tipo);
        }

        // Filtro por tipo de negocio (Venta o Arriendo)
        if ($r->filled('negocio')) {
            $q->where('id_tipo_negocio', $r->negocio);
        }

        // Precio mínimo
        if ($r->filled('min')) {
            $q->where('valor', '>=', $r->min);
        }

        // Precio máximo
        if ($r->filled('max')) {
            $q->where('valor', '<=', $r->max);
        }

        // Número mínimo de habitaciones
        if ($r->filled('habitaciones')) {
            $q->where('habitaciones', '>=', $r->habitaciones);
        }

        // Buscar por título
        if ($r->filled('buscar')) {
            $q->where('titulo', 'like', "%{$r->buscar}%");
        }

        // Ordenar por fecha de publicación (más recientes primero)
        // Mostrar 9 propiedades por página
        $inmuebles = $q->orderByDesc('fecha_publicacion')
                       ->paginate(9)
                       ->withQueryString();

        // Obtener listas para los filtros
        $tipos = TipoInmueble::all();
        $negocios = TipoNegocio::all();

        // Mostrar vista propiedades.blade.php
        return view('pages.propiedades', compact(
            'inmuebles',
            'tipos',
            'negocios'
        ));
    }

    /**
     * Mostrar detalle de una propiedad
     */
    public function show($id)
    {
        // Buscar propiedad por ID
        // Si no existe, Laravel muestra error 404
        $inmueble = Inmueble::with(
            'imagenes',
            'imagenesHd',
            'tipo',
            'negocio',
            'asesor'
        )->findOrFail($id);

        // Incrementar contador de visitas
        $inmueble->increment('visitas');

        // Guardar información de la visita
        VisitaPropiedad::create([
            'id_inmueble' => $inmueble->id_inmueble,
            'ip' => request()->ip(),
            'user_agent' => substr(request()->userAgent() ?? '', 0, 250),
        ]);

        // Buscar propiedades similares
        $similares = Inmueble::with('imagenes')
            ->where('id_tipo_inmueble', $inmueble->id_tipo_inmueble)
            ->where('id_inmueble', '!=', $id)
            ->publicas()
            ->limit(3)
            ->get();

        // Lista de bancos para créditos
        $bancos = \App\Http\Controllers\CreditoController::BANCOS;

        // Mostrar detalle de la propiedad
        return view('pages.propiedad', compact(
            'inmueble',
            'similares',
            'bancos'
        ));
    }

    /**
     * Página Comprar
     * Muestra propiedades en venta
     */
    public function comprar(Request $r)
    {
        $inmuebles = Inmueble::with('imagenes','tipo')
            ->publicas()

            // Solo propiedades de venta
            ->whereHas('negocio', function($q){
                $q->where('nombre_tipo', 'Venta');
            })

            ->orderByDesc('fecha_publicacion')
            ->paginate(6);

        return view('pages.comprar', compact('inmuebles'));
    }

    /**
     * Página Arrendar
     * Muestra propiedades en arriendo
     */
    public function arrendar(Request $r)
    {
        $inmuebles = Inmueble::with('imagenes','tipo')
            ->publicas()

            // Solo propiedades de arriendo
            ->whereHas('negocio', function($q){
                $q->where('nombre_tipo', 'Arriendo');
            })

            ->orderByDesc('fecha_publicacion')
            ->paginate(6);

        return view('pages.arrendar', compact('inmuebles'));
    }

    /**
     * API AJAX
     * Cargar más propiedades
     *
     * URL:
     * /api/propiedades/ver-mas
     */
    public function verMas(Request $r)
    {
        // Categoría recibida desde JavaScript
        // venta o arriendo
        $categoria = $r->get('categoria', 'venta');

        $q = Inmueble::with('imagenes','tipo')
            ->publicas()

            // Filtrar según la categoría
            ->whereHas('negocio', function($qq) use ($categoria){

                $qq->where(
                    'nombre_tipo',
                    $categoria === 'arriendo'
                        ? 'Arriendo'
                        : 'Venta'
                );

            })

            ->orderByDesc('fecha_publicacion')

            // Máximo 30 resultados
            ->limit(30);

        // Devuelve JSON
        return response()->json($q->get());
    }

    /**
     * API pública de propiedades
     *
     * URL:
     * /api/propiedades
     */
    public function apiList(Request $r)
    {
        // Consulta propiedades públicas
        $q = Inmueble::with(
            'imagenes',
            'tipo',
            'negocio'
        )->publicas();

        // Buscar por título
        if ($r->filled('buscar')) {

            $q->where(
                'titulo',
                'like',
                "%{$r->buscar}%"
            );
        }

        // Devuelve máximo 20 propiedades en JSON
        return response()->json(
            $q->limit(20)->get()
        );
    }
}