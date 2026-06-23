<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Inmueble;
use App\Models\Cliente;
use App\Models\Solicitud;
use App\Models\User;
use App\Models\Credito;
use App\Models\VisitaPropiedad;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'inmuebles'           => Inmueble::count(),
            'clientes'            => Cliente::count(),
            'usuarios'            => User::count(),
            'solicitudes'         => Solicitud::count(),
            'pendientes'          => Solicitud::where('estado','pendiente')->count(),
            'vendidos'            => Inmueble::where('estado_propiedad','Vendida')->count(),
            'arrendados'          => Inmueble::where('estado_propiedad','Arrendada')->count(),
            'disponibles'         => Inmueble::where('estado_propiedad','Disponible')->count(),
            'creditos_solicitados'=> Credito::count(),
            'creditos_aprobados'  => Credito::where('estado','Aprobado')->count(),
            'creditos_rechazados' => Credito::where('estado','Rechazado')->count(),
            'visitas'             => (int) (VisitaPropiedad::count() + Inmueble::sum('visitas')),
        ];

        $ultimasSolicitudes = Solicitud::with('cliente','inmueble')->orderByDesc('fecha')->limit(5)->get();
        $porCiudad = Inmueble::selectRaw('ciudad, count(*) as total')->groupBy('ciudad')->orderByDesc('total')->limit(6)->get();

        $dias = collect(range(13,0))->map(fn($d)=>now()->subDays($d)->format('Y-m-d'));
        $creditosSerie = $dias->map(fn($f)=>[
            'fecha' => $f,
            'total' => Credito::whereDate('fecha_solicitud',$f)->count(),
        ]);

        return view('admin.dashboard', compact('stats','ultimasSolicitudes','porCiudad','creditosSerie'));
    }

    public function live()
    {
        return response()->json([
            'creditos_solicitados' => Credito::count(),
            'creditos_aprobados'   => Credito::where('estado','Aprobado')->count(),
            'creditos_rechazados'  => Credito::where('estado','Rechazado')->count(),
            'vendidos'             => Inmueble::where('estado_propiedad','Vendida')->count(),
            'disponibles'          => Inmueble::where('estado_propiedad','Disponible')->count(),
            'visitas'              => (int) (VisitaPropiedad::count() + Inmueble::sum('visitas')),
            'usuarios'             => User::count(),
            'ts'                   => now()->toIso8601String(),
        ]);
    }
}
