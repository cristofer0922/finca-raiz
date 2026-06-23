<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\TipoUsuario;

class UserController extends Controller
{
    public function index()
    {
        $usuarios = User::with('tipo')->orderByDesc('id_usuario')->paginate(15);
        return view('admin.users.index', compact('usuarios'));
    }

    public function create() { return view('admin.users.form', ['usuario' => new User(), 'tipos' => TipoUsuario::all()]); }

    public function store(Request $r)
    {
        $data = $r->validate([
            'usuario' => 'required|max:50|unique:usuarios,usuario',
            'correo' => 'required|email|max:100|unique:usuarios,correo',
            'contrasena' => 'required|min:6',
            'id_tipo_usuario' => 'required|integer',
            'estado' => 'required|in:activo,inactivo,bloqueado',
        ]);
        $data['contrasena'] = Hash::make($data['contrasena']);
        User::create($data);
        return redirect()->route('admin.usuarios.index')->with('success','Usuario creado');
    }

    public function edit($id) { return view('admin.users.form', ['usuario' => User::findOrFail($id), 'tipos' => TipoUsuario::all()]); }

    public function update(Request $r, $id)
    {
        $u = User::findOrFail($id);
        $data = $r->validate([
            'usuario' => 'required|max:50|unique:usuarios,usuario,'.$id.',id_usuario',
            'correo' => 'required|email|max:100|unique:usuarios,correo,'.$id.',id_usuario',
            'contrasena' => 'nullable|min:6',
            'id_tipo_usuario' => 'required|integer',
            'estado' => 'required|in:activo,inactivo,bloqueado',
        ]);
        if (!empty($data['contrasena'])) $data['contrasena'] = Hash::make($data['contrasena']);
        else unset($data['contrasena']);
        $u->update($data);
        return redirect()->route('admin.usuarios.index')->with('success','Usuario actualizado');
    }

    public function destroy($id) { User::findOrFail($id)->delete(); return back()->with('success','Eliminado'); }
    public function show($id) { return redirect()->route('admin.usuarios.edit', $id); }
}
