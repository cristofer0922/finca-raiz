<?php
namespace App\Services;

use Illuminate\Support\Facades\Http;
use App\Models\ApiLog;

class MercadoPagoService
{
    protected ?string $token;

    public function __construct()
    {
        $this->token = config('services.mercadopago.token');
    }

    public function enabled(): bool { return !empty($this->token); }

    /**
     * Crea una preferencia de pago y devuelve la URL de checkout.
     * $items = [['title'=>...,'quantity'=>1,'unit_price'=>10000,'currency_id'=>'COP']]
     */
    public function crearPreferencia(array $items, array $meta = []): ?array
    {
        if (!$this->enabled()) return null;
        try {
            $payload = [
                'items' => $items,
                'metadata' => $meta,
                'back_urls' => [
                    'success' => route('pagos.success'),
                    'failure' => route('pagos.failure'),
                    'pending' => route('pagos.pending'),
                ],
                'auto_return' => 'approved',
                'notification_url' => route('pagos.webhook.mp'),
            ];
            $res = Http::withToken($this->token)
                ->post('https://api.mercadopago.com/checkout/preferences', $payload);
            ApiLog::create([
                'endpoint'=>'mercadopago/preferences','metodo'=>'POST',
                'status'=>$res->status(),'ip'=>request()->ip(),
                'payload'=>substr(json_encode($payload),0,1000),
            ]);
            if (!$res->successful()) return null;
            return [
                'id'    => $res->json('id'),
                'init'  => $res->json('init_point'),
                'sand'  => $res->json('sandbox_init_point'),
            ];
        } catch (\Throwable $e) { return null; }
    }

    public function obtenerPago(string $id): ?array
    {
        if (!$this->enabled()) return null;
        $res = Http::withToken($this->token)->get("https://api.mercadopago.com/v1/payments/{$id}");
        return $res->successful() ? $res->json() : null;
    }
}
