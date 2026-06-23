@extends('layouts.admin')
@section('page','Clientes')
@section('content')
<div class="admin-card">
    <div class="d-flex justify-content-between mb-3">
        <h5 class="serif mb-0">Clientes</h5>
        <a href="{{ route('admin.clientes.create') }}" class="btn btn-gold btn-sm"><i class="fas fa-plus"></i> Nuevo</a>
    </div>
    <table class="table-admin">
        <thead><tr><th>#</th><th>Nombre</th><th>Correo</th><th>Celular</th><th>Ciudad</th><th>Tipo</th><th></th></tr></thead>
        <tbody>
        @foreach($clientes as $c)
            <tr><td>{{$c->id_cliente}}</td><td>{{$c->nombre_completo}}</td><td>{{$c->correo}}</td><td>{{$c->celular}}</td><td>{{$c->ciudad}}</td><td>{{$c->tipo->nombre_tipo ?? '—'}}</td>
            <td>
                <a href="{{ route('admin.clientes.edit',$c->id_cliente) }}" class="btn btn-sm btn-outline-dark"><i class="fas fa-edit"></i></a>
                <form action="{{ route('admin.clientes.destroy',$c->id_cliente) }}" method="POST" class="d-inline">@csrf @method('DELETE')
                    <button type="button" class="btn btn-sm btn-outline-danger btn-delete"><i class="fas fa-trash"></i></button>
                </form>
            </td></tr>
        @endforeach
        </tbody>
    </table>
    <div class="mt-3">{{ $clientes->links('pagination::bootstrap-5') }}</div>
</div>
@endsection
