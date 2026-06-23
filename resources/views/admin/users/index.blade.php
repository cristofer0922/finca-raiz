@extends('layouts.admin')
@section('page','Usuarios')
@section('content')
<div class="admin-card">
    <div class="d-flex justify-content-between mb-3"><h5 class="serif mb-0">Usuarios</h5>
        <a href="{{ route('admin.usuarios.create') }}" class="btn btn-gold btn-sm"><i class="fas fa-plus"></i> Nuevo</a></div>
    <table class="table-admin">
        <thead><tr><th>#</th><th>Usuario</th><th>Correo</th><th>Rol</th><th>Estado</th><th></th></tr></thead>
        <tbody>
        @foreach($usuarios as $u)
            <tr><td>{{$u->id_usuario}}</td><td>{{$u->usuario}}</td><td>{{$u->correo}}</td><td>{{$u->tipo->nombre_tipo ?? '—'}}</td>
            <td><span class="badge-est {{$u->estado}}">{{$u->estado}}</span></td>
            <td>
                <a href="{{ route('admin.usuarios.edit',$u->id_usuario) }}" class="btn btn-sm btn-outline-dark"><i class="fas fa-edit"></i></a>
                <form action="{{ route('admin.usuarios.destroy',$u->id_usuario) }}" method="POST" class="d-inline">@csrf @method('DELETE')
                    <button type="button" class="btn btn-sm btn-outline-danger btn-delete"><i class="fas fa-trash"></i></button>
                </form>
            </td></tr>
        @endforeach
        </tbody>
    </table>
    <div class="mt-3">{{ $usuarios->links('pagination::bootstrap-5') }}</div>
</div>
@endsection
