<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ChatMensaje;
use App\Models\Inmueble;
use App\Services\OpenAiService;

class ChatIaController extends Controller
{
    public function send(Request $r, OpenAiService $ia)
    {
        $r->validate([
            'mensaje' => 'required|string|max:1000',
            'sesion'  => 'nullable|string|max:80',
        ]);
        $sesion = $r->sesion ?: session()->getId();
        $texto  = trim($r->mensaje);

        ChatMensaje::create(['sesion'=>$sesion,'rol'=>'user','mensaje'=>$texto]);

        $respuesta = null;

        if ($ia->enabled()) {
            // Catálogo reducido para el contexto
            $catalogo = Inmueble::where('estado_propiedad','Disponible')
                ->orderByDesc('destacado')->limit(20)
                ->get(['id_inmueble','titulo','ciudad','barrio','valor','habitaciones','banos','area'])
                ->toArray();

            // Recuperar historial reciente
            $history = ChatMensaje::where('sesion',$sesion)
                ->orderByDesc('id')->limit(8)->get()->reverse()
                ->map(fn($m)=>['role'=>$m->rol,'content'=>$m->mensaje])->values()->all();

            $messages = array_merge([
                ['role'=>'system','content'=>'Eres el asistente inmobiliario premium de FincaRaízPro. Recomiendas propiedades, simulas créditos (tasa 1.2% mensual) y analizas presupuestos. Responde en español, breve y elegante. Cuando recomiendes propiedades, usa los datos del catálogo proporcionado.'],
                ['role'=>'system','content'=>'Catálogo disponible (JSON): '.json_encode($catalogo, JSON_UNESCAPED_UNICODE)],
            ], $history);

            $respuesta = $ia->chat($messages, 0.6);
        }

        if (!$respuesta) {
            $respuesta = $this->fallback(mb_strtolower($texto));
        }

        ChatMensaje::create(['sesion'=>$sesion,'rol'=>'assistant','mensaje'=>$respuesta]);

        return response()->json(['ok'=>true,'respuesta'=>$respuesta,'sesion'=>$sesion]);
    }

    private function fallback(string $t): string
    {
        if (preg_match('/simul.*cr[eé]dito|cuota|financ/u', $t)) {
            preg_match('/(\d[\d\.\,]{4,})/', $t, $m);
            $valor = $m[1] ?? null;
            $valor = $valor ? (float) str_replace(['.',','], '', $valor) : 300000000;
            $tasa  = 0.012; $plazo = 240;
            $cuota = $valor * $tasa / (1 - pow(1+$tasa, -$plazo));
            return "Para un valor de $".number_format($valor,0,',','.').", a 20 años (1.2% mensual), tu cuota estimada sería $".number_format($cuota,0,',','.').".";
        }
        if (preg_match('/recomi|sugerencia|busco|necesito|quiero/u', $t)) {
            preg_match('/(\d[\d\.\,]{4,})/', $t, $m);
            $max = $m[1] ?? null;
            $max = $max ? (float) str_replace(['.',','], '', $max) : null;
            $q = Inmueble::where('estado_propiedad','Disponible');
            if ($max) $q->where('valor','<=',$max);
            $ops = $q->orderBy('valor')->limit(3)->get(['id_inmueble','titulo','ciudad','valor']);
            if ($ops->isEmpty()) return "No encontré propiedades con esos criterios. Cuéntame ciudad, presupuesto o tipo.";
            return "Te recomiendo:\n".$ops->map(fn($i)=>"• {$i->titulo} ({$i->ciudad}) — $".number_format($i->valor,0,',','.'))->implode("\n");
        }
        if (preg_match('/presupuesto|cu[aá]nto.*gan/u', $t)) {
            return "Con un ingreso mensual estable, normalmente puedes acceder a un crédito cuya cuota no supere el 30% de tus ingresos. ¿Cuál es tu ingreso mensual?";
        }
        return "Hola, soy tu asistente inmobiliario. Puedo recomendarte propiedades, simular un crédito o responder dudas. ¿En qué te ayudo?";
    }
}
