<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Cliente;
use App\Models\Solicitud;

class RequestController extends Controller
{
    public function store(Request $r)
    {
        $data = $r->validate([
            'p_nombre' => 'required|string|max:50',
            'p_apellido' => 'required|string|max:50',
            'documento' => 'required|string|max:30',
            'celular' => 'required|string|max:20',
            'correo' => 'required|email|max:100',
            'direccion' => 'nullable|string|max:150',
            'ciudad' => 'nullable|string|max:100',
            'fecha' => 'nullable|date',
            'tipo_solicitud' => 'required|in:compra,arriendo,visita',
            'mensaje' => 'nullable|string|max:1000',
            'id_inmueble' => 'nullable|integer|exists:inmuebles,id_inmueble',
        ]);

        $cliente = Cliente::updateOrCreate(
            ['correo' => $data['correo']],
            [
                'p_nombre' => $data['p_nombre'],
                'p_apellido' => $data['p_apellido'],
                'documento' => $data['documento'],
                'celular' => $data['celular'],
                'direccion' => $data['direccion'] ?? null,
                'ciudad' => $data['ciudad'] ?? null,
                'id_tipo_cliente' => $data['tipo_solicitud'] === 'arriendo' ? 2 : 1,
            ]
        );

        Solicitud::create([
            'id_cliente' => $cliente->id_cliente,
            'id_inmueble' => $data['id_inmueble'] ?? null,
            'tipo_solicitud' => $data['tipo_solicitud'],
            'mensaje' => $data['mensaje'] ?? null,
            'estado' => 'pendiente',
        ]);

        if ($r->wantsJson()) {
            return response()->json(['ok' => true, 'message' => 'Solicitud enviada con éxito']);
        }
        return back()->with('success', '¡Tu solicitud fue enviada con éxito! Te contactaremos pronto.');
    }
}
