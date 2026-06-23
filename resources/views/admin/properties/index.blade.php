@extends('layouts.admin')
@section('page','Propiedades')
@section('content')
<div class="admin-card">
    <div class="d-flex justify-content-between mb-3">
        <h5 class="serif mb-0">Listado de propiedades</h5>
        <a href="{{ route('admin.propiedades.create') }}" class="btn btn-gold btn-sm"><i class="fas fa-plus"></i> Nueva</a>
    </div>
    <div class="table-responsive">
    <table class="table-admin">
        <thead><tr><th>#</th><th>Imagen</th><th>Título</th><th>Ciudad</th><th>Valor</th><th>Estado</th><th>Acciones</th></tr></thead>
        <tbody>
        @foreach($inmuebles as $i)
            <tr>
                <td>{{ $i->id_inmueble }}</td>
                <td><img src="{{ $i->imagen_principal }}" style="width:60px;height:50px;object-fit:cover;border-radius:8px"></td>
                <td>{{ Str::limit($i->titulo,40) }}</td>
                <td>{{ $i->ciudad }}</td>
                <td>${{ number_format($i->valor,0,',','.') }}</td>
                <td><span class="badge-est {{ $i->estado }}">{{ $i->estado }}</span></td>
                <td>
                    <a href="{{ route('admin.propiedades.edit', $i->id_inmueble) }}" class="btn btn-sm btn-outline-dark"><i class="fas fa-edit"></i></a>
                    <form action="{{ route('admin.propiedades.destroy', $i->id_inmueble) }}" method="POST" class="d-inline">@csrf @method('DELETE')
                        <button type="button" class="btn btn-sm btn-outline-danger btn-delete"><i class="fas fa-trash"></i></button>
                    </form>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
    </div>
    <div class="mt-3">{{ $inmuebles->links('pagination::bootstrap-5') }}</div>
</div>
@endsection
