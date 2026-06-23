<?php
namespace App\Http\Controllers\Agent;

use App\Http\Controllers\Controller;
use App\Models\Credito;
use App\Models\SolicitudInformacion;
use App\Models\Inmueble;
use App\Models\VisitaPropiedad;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'creditos_solicitados' => Credito::count(),
            'creditos_aprobados'   => Credito::where('estado','Aprobado')->count(),
            'creditos_rechazados'  => Credito::where('estado','Rechazado')->count(),
            'creditos_pendientes'  => Credito::where('estado','Pendiente')->count(),
            'casas_vendidas'       => Inmueble::where('estado_propiedad','Vendida')->count(),
            'casas_disponibles'    => Inmueble::where('estado_propiedad','Disponible')->count(),
            'casas_arrendadas'     => Inmueble::where('estado_propiedad','Arrendada')->count(),
            'casas_reservadas'     => Inmueble::where('estado_propiedad','Reservada')->count(),
            'visitas'              => (int) (VisitaPropiedad::count() + Inmueble::sum('visitas')),
            'usuarios'             => User::count(),
            'solicitudes_info'     => SolicitudInformacion::count(),
        ];

        // Datos para gráficas en tiempo real (últimos 14 días)
        $dias = collect(range(13, 0))->map(fn($d) => now()->subDays($d)->format('Y-m-d'));

        $creditosPorDia = $dias->map(function ($fecha) {
            return [
                'fecha' => $fecha,
                'total' => Credito::whereDate('fecha_solicitud', $fecha)->count(),
                'aprob' => Credito::whereDate('fecha_solicitud', $fecha)->where('estado','Aprobado')->count(),
            ];
        });

        $visitasPorDia = $dias->map(fn($f) => [
            'fecha' => $f,
            'total' => VisitaPropiedad::whereDate('fecha', $f)->count(),
        ]);

        $porBanco = Credito::select('banco', DB::raw('count(*) as total'))
            ->groupBy('banco')->orderByDesc('total')->get();

        return view('agent.dashboard', compact('stats','creditosPorDia','visitasPorDia','porBanco'));
    }

    // Endpoint JSON usado por las gráficas para refresco en vivo
    public function live(Request $r)
    {
        return response()->json([
            'creditos_solicitados' => Credito::count(),
            'creditos_aprobados'   => Credito::where('estado','Aprobado')->count(),
            'creditos_rechazados'  => Credito::where('estado','Rechazado')->count(),
            'casas_vendidas'       => Inmueble::where('estado_propiedad','Vendida')->count(),
            'casas_disponibles'    => Inmueble::where('estado_propiedad','Disponible')->count(),
            'visitas'              => (int) (VisitaPropiedad::count() + Inmueble::sum('visitas')),
            'usuarios'             => User::count(),
            'ts'                   => now()->toIso8601String(),
        ]);
    }
}
