@extends('layouts.admin')
@section('page', $cliente->exists?'Editar cliente':'Nuevo cliente')
@section('content')
<div class="admin-card">
<form method="POST" action="{{ $cliente->exists ? route('admin.clientes.update',$cliente->id_cliente) : route('admin.clientes.store') }}" class="row g-3">@csrf
    @if($cliente->exists)@method('PUT')@endif
    <div class="col-md-6"><label>Primer nombre</label><input class="form-control" name="p_nombre" value="{{ old('p_nombre',$cliente->p_nombre) }}" required></div>
    <div class="col-md-6"><label>Segundo nombre</label><input class="form-control" name="s_nombre" value="{{ old('s_nombre',$cliente->s_nombre) }}"></div>
    <div class="col-md-6"><label>Primer apellido</label><input class="form-control" name="p_apellido" value="{{ old('p_apellido',$cliente->p_apellido) }}" required></div>
    <div class="col-md-6"><label>Segundo apellido</label><input class="form-control" name="s_apellido" value="{{ old('s_apellido',$cliente->s_apellido) }}"></div>
    <div class="col-md-6"><label>Celular</label><input class="form-control" name="celular" value="{{ old('celular',$cliente->celular) }}" required></div>
    <div class="col-md-6"><label>Correo</label><input class="form-control" type="email" name="correo" value="{{ old('correo',$cliente->correo) }}" required></div>
    <div class="col-md-6"><label>Documento</label><input class="form-control" name="documento" value="{{ old('documento',$cliente->documento) }}"></div>
    <div class="col-md-6"><label>Ciudad</label><input class="form-control" name="ciudad" value="{{ old('ciudad',$cliente->ciudad) }}"></div>
    <div class="col-12"><label>Dirección</label><input class="form-control" name="direccion" value="{{ old('direccion',$cliente->direccion) }}"></div>
    <div class="col-md-6"><label>Fecha nacimiento</label><input class="form-control" type="date" name="fecha_nacimiento" value="{{ old('fecha_nacimiento',$cliente->fecha_nacimiento) }}"></div>
    <div class="col-md-6"><label>Tipo</label><select class="form-select" name="id_tipo_cliente">
        @foreach($tipos as $t)<option value="{{$t->id_tipo_cliente}}" @selected(old('id_tipo_cliente',$cliente->id_tipo_cliente)==$t->id_tipo_cliente)>{{$t->nombre_tipo}}</option>@endforeach
    </select></div>
    <div class="col-12"><button class="btn btn-gold"><i class="fas fa-save"></i> Guardar</button>
        <a href="{{ route('admin.clientes.index') }}" class="btn btn-outline-dark">Cancelar</a></div>
</form>
</div>
@endsection
