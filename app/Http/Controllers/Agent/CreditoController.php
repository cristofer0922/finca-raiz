<?php
namespace App\Http\Controllers\Agent;

use App\Http\Controllers\Controller;
use App\Models\Credito;
use Illuminate\Http\Request;

class CreditoController extends Controller
{
    public function index(Request $r)
    {
        $q = Credito::query();
        if ($r->filled('estado')) $q->where('estado', $r->estado);
        if ($r->filled('banco')) $q->where('banco', $r->banco);
        $creditos = $q->orderByDesc('fecha_solicitud')->paginate(15)->withQueryString();
        return view('agent.creditos.index', compact('creditos'));
    }

    public function show($id)
    {
        $credito = Credito::with('inmueble')->findOrFail($id);
        return view('agent.creditos.show', compact('credito'));
    }

    public function aprobar($id)
    {
        $c = Credito::findOrFail($id);
        $c->estado = 'Aprobado';
        $c->fecha_decision = now();
        $c->save();
        return back()->with('success', 'Crédito aprobado.');
    }

    public function rechazar($id)
    {
        $c = Credito::findOrFail($id);
        $c->estado = 'Rechazado';
        $c->fecha_decision = now();
        $c->save();
        return back()->with('success', 'Crédito rechazado.');
    }

    // Reporte PDF (HTML imprimible)
    public function reportePdf(Request $r)
    {
        $creditos = Credito::orderByDesc('fecha_solicitud')->get();
        $html = view('agent.creditos.pdf', compact('creditos'))->render();
        return response($html, 200, [
            'Content-Type' => 'text/html; charset=UTF-8',
            'Content-Disposition' => 'inline; filename="reporte-creditos.html"',
        ]);
    }

    // Exportar Excel (CSV compatible)
    public function exportExcel()
    {
        $creditos = Credito::orderByDesc('fecha_solicitud')->get();
        $filename = 'creditos-'.now()->format('Ymd-His').'.csv';
        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => "attachment; filename=\"$filename\"",
        ];
        $callback = function() use ($creditos) {
            $out = fopen('php://output', 'w');
            fwrite($out, "\xEF\xBB\xBF"); // BOM para Excel
            fputcsv($out, ['ID','Nombre','Documento','Correo','Teléfono','Ingresos','Tipo Contrato','Empresa','Banco','Valor Propiedad','Cuota Inicial','Estado','Fecha']);
            foreach ($creditos as $c) {
                fputcsv($out, [
                    $c->id_credito, $c->nombre_completo, $c->documento, $c->correo, $c->telefono,
                    $c->ingresos_mensuales, $c->tipo_contrato, $c->empresa, $c->banco,
                    $c->valor_propiedad, $c->cuota_inicial, $c->estado, $c->fecha_solicitud,
                ]);
            }
            fclose($out);
        };
        return response()->stream($callback, 200, $headers);
    }
}
