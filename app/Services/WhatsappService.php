<?php
namespace App\Services;

use Illuminate\Support\Facades\Http;
use App\Models\ApiLog;
use App\Models\Setting;

class WhatsappService
{
    public function numero(): string
    {
        return Setting::get('whatsapp_number', (string) config('services.whatsapp.number'));
    }

    public function linkPropiedad($inmueble): string
    {
        $url   = route('propiedades.show', $inmueble->id_inmueble);
        $precio = number_format((float)$inmueble->valor, 0, ',', '.');
        $msg = "Hola, estoy interesado en la propiedad *{$inmueble->titulo}* "
             . "en {$inmueble->ciudad}. Precio: \${$precio}. "
             . "Más info: {$url}";
        return 'https://wa.me/'.$this->numero().'?text='.rawurlencode($msg);
    }

    public function enviarMensaje(string $to, string $mensaje): bool
    {
        $token   = config('services.whatsapp.token');
        $phoneId = config('services.whatsapp.phone_id');
        if (!$token || !$phoneId) return false;
        try {
            $res = Http::withToken($token)->post(
                "https://graph.facebook.com/v20.0/{$phoneId}/messages",
                [
                    'messaging_product' => 'whatsapp',
                    'to' => $to,
                    'type' => 'text',
                    'text' => ['body' => $mensaje],
                ]
            );
            ApiLog::create([
                'endpoint'=>'whatsapp/messages','metodo'=>'POST',
                'status'=>$res->status(),'ip'=>request()->ip(),
                'payload'=>substr($mensaje,0,500),
            ]);
            return $res->successful();
        } catch (\Throwable $e) { return false; }
    }
}
