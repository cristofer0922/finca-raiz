<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Cliente;
use App\Models\TipoCliente;

class ClientController extends Controller
{
    public function index()
    {
        $clientes = Cliente::with('tipo','usuario')->orderByDesc('id_cliente')->paginate(15);
        return view('admin.clients.index', compact('clientes'));
    }

    public function create()
    {
        return view('admin.clients.form', ['cliente' => new Cliente(), 'tipos' => TipoCliente::all()]);
    }

    public function store(Request $r)
    {
        $data = $this->v($r);
        Cliente::create($data);
        return redirect()->route('admin.clientes.index')->with('success','Cliente creado');
    }

    public function edit($id)
    {
        return view('admin.clients.form', ['cliente' => Cliente::findOrFail($id), 'tipos' => TipoCliente::all()]);
    }

    public function update(Request $r, $id)
    {
        Cliente::findOrFail($id)->update($this->v($r, $id));
        return redirect()->route('admin.clientes.index')->with('success','Cliente actualizado');
    }

    public function destroy($id)
    {
        Cliente::findOrFail($id)->delete();
        return back()->with('success','Cliente eliminado');
    }

    public function show($id) { return redirect()->route('admin.clientes.edit', $id); }

    private function v(Request $r, $id = null): array
    {
        return $r->validate([
            'p_nombre' => 'required|max:50',
            's_nombre' => 'nullable|max:50',
            'p_apellido' => 'required|max:50',
            's_apellido' => 'nullable|max:50',
            'celular' => 'required|max:20',
            'correo' => 'required|email|max:100|unique:clientes,correo,'.($id??'NULL').',id_cliente',
            'direccion' => 'nullable|max:150',
            'ciudad' => 'nullable|max:100',
            'documento' => 'nullable|max:30',
            'fecha_nacimiento' => 'nullable|date',
            'id_tipo_cliente' => 'nullable|integer',
        ]);
    }
}
