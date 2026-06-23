<?php
namespace App\Services;

use Illuminate\Support\Facades\Http;
use App\Models\ApiLog;

class StripeService
{
    protected ?string $secret;

    public function __construct()
    {
        $this->secret = config('services.stripe.secret');
    }

    public function enabled(): bool { return !empty($this->secret); }

    protected function http()
    {
        return Http::asForm()->withToken($this->secret);
    }

    /** Crea una Checkout Session de pago único (USD por defecto). */
    public function crearCheckoutSession(string $nombre, int $montoCents, string $moneda='usd', array $meta=[]): ?string
    {
        if (!$this->enabled()) return null;
        $params = [
            'mode' => 'payment',
            'success_url' => route('pagos.success').'?sid={CHECKOUT_SESSION_ID}',
            'cancel_url'  => route('pagos.failure'),
            'line_items[0][price_data][currency]' => $moneda,
            'line_items[0][price_data][product_data][name]' => $nombre,
            'line_items[0][price_data][unit_amount]' => $montoCents,
            'line_items[0][quantity]' => 1,
        ];
        foreach ($meta as $k=>$v) $params["metadata[$k]"] = (string)$v;
        $res = $this->http()->post('https://api.stripe.com/v1/checkout/sessions', $params);
        ApiLog::create(['endpoint'=>'stripe/checkout','metodo'=>'POST','status'=>$res->status(),'ip'=>request()->ip(),'payload'=>$nombre]);
        return $res->json('url');
    }

    /** Crea una Checkout Session de suscripción usando un price recurrente predefinido. */
    public function crearSuscripcion(string $priceId, array $meta=[]): ?string
    {
        if (!$this->enabled()) return null;
        $params = [
            'mode' => 'subscription',
            'success_url' => route('pagos.success').'?sid={CHECKOUT_SESSION_ID}',
            'cancel_url'  => route('pagos.failure'),
            'line_items[0][price]' => $priceId,
            'line_items[0][quantity]' => 1,
        ];
        foreach ($meta as $k=>$v) $params["metadata[$k]"] = (string)$v;
        $res = $this->http()->post('https://api.stripe.com/v1/checkout/sessions', $params);
        ApiLog::create(['endpoint'=>'stripe/subscription','metodo'=>'POST','status'=>$res->status(),'ip'=>request()->ip(),'payload'=>$priceId]);
        return $res->json('url');
    }

    public function verificarFirma(string $payload, string $sigHeader): bool
    {
        $secret = config('services.stripe.webhook_secret');
        if (!$secret || !$sigHeader) return false;
        $parts = [];
        foreach (explode(',', $sigHeader) as $part) {
            [$k,$v] = array_pad(explode('=', $part, 2), 2, null);
            $parts[$k] = $v;
        }
        if (empty($parts['t']) || empty($parts['v1'])) return false;
        $signed = $parts['t'].'.'.$payload;
        $expected = hash_hmac('sha256', $signed, $secret);
        return hash_equals($expected, $parts['v1']);
    }
}
