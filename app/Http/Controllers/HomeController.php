<?php

namespace App\Http\Controllers;

use App\Models\Inmueble;
use App\Models\Cliente;
use App\Models\Solicitud;

class HomeController extends Controller
{
    public function index()
    {
        $destacadas = Inmueble::with('imagenes', 'tipo', 'negocio')
            ->where('estado', 'disponible')
            ->orderByDesc('valor')
            ->limit(6)->get();

        $recientes = Inmueble::with('imagenes', 'tipo', 'negocio')
            ->where('estado', 'disponible')
            ->orderByDesc('fecha_registro')
            ->limit(8)->get();

        $stats = [
            'inmuebles' => Inmueble::count(),
            'clientes' => Cliente::count(),
            'vendidas' => Inmueble::where('estado', 'vendido')->count(),
            'arrendadas' => Inmueble::where('estado', 'arrendado')->count(),
        ];

        return view('pages.home', compact('destacadas', 'recientes', 'stats'));
    }
}
