@extends('layouts.admin')
@section('page', $usuario->exists?'Editar usuario':'Nuevo usuario')
@section('content')
<div class="admin-card">
<form method="POST" action="{{ $usuario->exists?route('admin.usuarios.update',$usuario->id_usuario):route('admin.usuarios.store') }}" class="row g-3">@csrf
    @if($usuario->exists)@method('PUT')@endif
    <div class="col-md-6"><label>Usuario</label><input class="form-control" name="usuario" value="{{ old('usuario',$usuario->usuario) }}" required></div>
    <div class="col-md-6"><label>Correo</label><input class="form-control" type="email" name="correo" value="{{ old('correo',$usuario->correo) }}" required></div>
    <div class="col-md-6"><label>Contraseña {{ $usuario->exists?'(dejar vacío para no cambiar)':'' }}</label><input class="form-control" type="password" name="contrasena"></div>
    <div class="col-md-3"><label>Rol</label><select class="form-select" name="id_tipo_usuario" required>
        @foreach($tipos as $t)<option value="{{$t->id_tipo_usuario}}" @selected(old('id_tipo_usuario',$usuario->id_tipo_usuario)==$t->id_tipo_usuario)>{{$t->nombre_tipo}}</option>@endforeach
    </select></div>
    <div class="col-md-3"><label>Estado</label><select class="form-select" name="estado">
        @foreach(['activo','inactivo','bloqueado'] as $e)<option @selected(old('estado',$usuario->estado)===$e)>{{$e}}</option>@endforeach
    </select></div>
    <div class="col-12"><button class="btn btn-gold"><i class="fas fa-save"></i> Guardar</button>
        <a href="{{ route('admin.usuarios.index') }}" class="btn btn-outline-dark">Cancelar</a></div>
</form>
</div>
@endsection
