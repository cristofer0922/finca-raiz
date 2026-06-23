<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\MercadoPagoService;
use App\Services\StripeService;
use App\Models\Inmueble;
use App\Models\Plan;
use App\Models\Transaccion;
use App\Models\Suscripcion;

class PaymentController extends Controller
{
    /** Página de planes / destacar propiedad. */
    public function planes()
    {
        $planes = Plan::where('activo',true)->get();
        return view('pages.planes', compact('planes'));
    }

    /** Inicia pago MercadoPago para destacar propiedad. */
    public function destacarMP(Request $r, MercadoPagoService $mp)
    {
        $r->validate(['id_inmueble'=>'required|integer','id_plan'=>'required|integer']);
        $plan = Plan::findOrFail($r->id_plan);
        $inm  = Inmueble::findOrFail($r->id_inmueble);

        $pref = $mp->crearPreferencia(
            items: [[
                'title' => $plan->nombre.' — '.$inm->titulo,
                'quantity' => 1,
                'unit_price' => (float)$plan->precio,
                'currency_id' => $plan->moneda,
            ]],
            meta: ['id_inmueble'=>$inm->id_inmueble,'id_plan'=>$plan->id_plan,'tipo'=>'destacar','dias'=>$plan->duracion_dias],
        );
        if (!$pref) return back()->with('error','MercadoPago no configurado');

        Transaccion::create([
            'id_inmueble'=>$inm->id_inmueble,'monto'=>$plan->precio,'tipo'=>'destacar',
            'proveedor'=>'mercadopago','referencia'=>$pref['id'],'estado'=>'Pendiente',
            'id_usuario'=>session('user.id'),
            'meta'=>json_encode(['id_plan'=>$plan->id_plan,'dias'=>$plan->duracion_dias]),
        ]);
        return redirect()->away($pref['init'] ?? $pref['sand']);
    }

    /** Inicia suscripción Stripe (mensual) para agente. */
    public function suscribirStripe(Request $r, StripeService $st)
    {
        $r->validate(['id_plan'=>'required|integer']);
        $plan = Plan::findOrFail($r->id_plan);
        $userId = session('user.id');
        if (!$userId) return redirect()->route('login');

        if ($plan->stripe_price_id) {
            $url = $st->crearSuscripcion($plan->stripe_price_id, ['id_usuario'=>$userId,'id_plan'=>$plan->id_plan]);
        } else {
            $url = $st->crearCheckoutSession($plan->nombre, (int) round($plan->precio*100), strtolower($plan->moneda ?: 'usd'),
                ['id_usuario'=>$userId,'id_plan'=>$plan->id_plan]);
        }
        if (!$url) return back()->with('error','Stripe no configurado');

        Suscripcion::create([
            'id_usuario'=>$userId,'id_plan'=>$plan->id_plan,'proveedor'=>'stripe',
            'estado'=>'Pendiente','inicio'=>now(),
        ]);
        return redirect()->away($url);
    }

    public function success() { return view('pages.pago-success'); }
    public function failure() { return view('pages.pago-failure'); }
    public function pending() { return view('pages.pago-pending'); }

    /** Webhook MercadoPago. */
    public function webhookMercadoPago(Request $r, MercadoPagoService $mp)
    {
        $id = $r->input('data.id') ?? $r->query('id');
        if (!$id) return response()->json(['ok'=>true]);
        $pago = $mp->obtenerPago((string)$id);
        if (!$pago) return response()->json(['ok'=>true]);

        $ref = $pago['order']['id'] ?? $pago['external_reference'] ?? null;
        $estado = $pago['status'] ?? 'pending';
        $meta = $pago['metadata'] ?? [];

        $trx = Transaccion::where('proveedor','mercadopago')
            ->where(function($q) use ($ref, $meta){
                if ($ref) $q->where('referencia',$ref);
                if (!empty($meta['id_inmueble'])) $q->orWhere('id_inmueble',$meta['id_inmueble']);
            })->latest('id_transaccion')->first();

        if ($trx) {
            $trx->estado = $estado === 'approved' ? 'Completada' : ($estado === 'rejected' ? 'Cancelada' : 'Pendiente');
            $trx->save();
        }
        if ($estado === 'approved' && !empty($meta['id_inmueble']) && !empty($meta['dias'])) {
            $inm = Inmueble::find($meta['id_inmueble']);
            if ($inm) {
                $inm->destacado = true;
                $inm->destacado_hasta = now()->addDays((int)$meta['dias']);
                $inm->save();
            }
        }
        return response()->json(['ok'=>true]);
    }

    /** Webhook Stripe. */
    public function webhookStripe(Request $r, StripeService $st)
    {
        $payload = $r->getContent();
        $sig = $r->header('Stripe-Signature','');
        if (!$st->verificarFirma($payload, $sig)) {
            return response()->json(['ok'=>false], 400);
        }
        $event = json_decode($payload, true);
        $type = $event['type'] ?? '';
        $obj  = $event['data']['object'] ?? [];
        $meta = $obj['metadata'] ?? [];

        if ($type === 'checkout.session.completed') {
            $monto = ($obj['amount_total'] ?? 0)/100;
            Transaccion::create([
                'id_inmueble'=>$meta['id_inmueble'] ?? null,
                'monto'=>$monto,'tipo'=>($obj['mode'] ?? 'payment'),
                'proveedor'=>'stripe','referencia'=>$obj['id'] ?? null,
                'estado'=>'Completada','id_usuario'=>$meta['id_usuario'] ?? null,
                'meta'=>json_encode($meta),
            ]);
            if (($obj['mode'] ?? '')==='subscription' && !empty($meta['id_usuario']) && !empty($meta['id_plan'])) {
                $plan = Plan::find($meta['id_plan']);
                Suscripcion::updateOrCreate(
                    ['id_usuario'=>$meta['id_usuario'],'id_plan'=>$meta['id_plan'],'proveedor'=>'stripe'],
                    ['referencia'=>$obj['subscription'] ?? $obj['id'],'estado'=>'Activa',
                     'inicio'=>now(),'fin'=>now()->addDays($plan->duracion_dias ?? 30)]
                );
            }
        } elseif ($type === 'customer.subscription.deleted') {
            Suscripcion::where('referencia',$obj['id'] ?? '')->update(['estado'=>'Cancelada','fin'=>now()]);
        }
        return response()->json(['ok'=>true]);
    }

    /** Historial del usuario. */
    public function historial()
    {
        $userId = session('user.id');
        $trx  = Transaccion::where('id_usuario',$userId)->orderByDesc('id_transaccion')->limit(100)->get();
        $subs = Suscripcion::with('plan')->where('id_usuario',$userId)->orderByDesc('id_suscripcion')->get();
        return view('pages.historial-pagos', compact('trx','subs'));
    }
}
