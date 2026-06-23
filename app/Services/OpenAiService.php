<?php
namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use App\Models\ApiLog;

class OpenAiService
{
    protected string $key;
    protected string $model;

    public function __construct()
    {
        $this->key   = (string) config('services.openai.key');
        $this->model = (string) config('services.openai.model');
    }

    public function enabled(): bool { return !empty($this->key); }

    public function chat(array $messages, float $temp = 0.7): ?string
    {
        if (!$this->enabled()) return null;
        try {
            $res = Http::withToken($this->key)
                ->timeout(30)
                ->post('https://api.openai.com/v1/chat/completions', [
                    'model' => $this->model,
                    'messages' => $messages,
                    'temperature' => $temp,
                ]);
            $this->log('chat/completions', $res->status(), $messages);
            return $res->json('choices.0.message.content');
        } catch (\Throwable $e) {
            Log::error('OpenAI error: '.$e->getMessage());
            return null;
        }
    }

    public function generarDescripcion(array $inmueble): ?string
    {
        $system = 'Eres un copywriter inmobiliario de lujo. Generas descripciones premium, persuasivas, en español, máximo 120 palabras, sin emojis.';
        $user = "Genera la descripción para: ".json_encode($inmueble, JSON_UNESCAPED_UNICODE);
        return $this->chat([
            ['role'=>'system','content'=>$system],
            ['role'=>'user','content'=>$user],
        ], 0.8);
    }

    public function recomendar(string $consulta, array $catalogo): ?string
    {
        $system = "Eres un asistente inmobiliario premium de FincaRaízPro. Recomiendas propiedades del catálogo proporcionado según presupuesto, ciudad y necesidades. Responde en español, breve, listando 1-3 opciones con id, título, ciudad y precio.";
        $user = "Consulta del usuario: {$consulta}\n\nCatálogo (JSON): ".json_encode($catalogo, JSON_UNESCAPED_UNICODE);
        return $this->chat([
            ['role'=>'system','content'=>$system],
            ['role'=>'user','content'=>$user],
        ], 0.5);
    }

    protected function log(string $endpoint, int $status, $payload): void
    {
        try {
            ApiLog::create([
                'endpoint' => 'openai/'.$endpoint,
                'metodo'   => 'POST',
                'status'   => $status,
                'ip'       => request()->ip(),
                'payload'  => substr(json_encode($payload, JSON_UNESCAPED_UNICODE), 0, 2000),
            ]);
        } catch (\Throwable $e) {}
    }
}
