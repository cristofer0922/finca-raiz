@extends('layouts.admin')
@section('page', $inmueble->exists ? 'Editar propiedad' : 'Nueva propiedad')
@section('content')
<div class="admin-card">
    <form method="POST" action="{{ $inmueble->exists ? route('admin.propiedades.update',$inmueble->id_inmueble) : route('admin.propiedades.store') }}" enctype="multipart/form-data" class="row g-3">@csrf
        @if($inmueble->exists) @method('PUT') @endif
        <div class="col-md-8"><label>Título</label><input name="titulo" class="form-control" value="{{ old('titulo',$inmueble->titulo) }}" required></div>
        <div class="col-md-4"><label>Estado</label><select name="estado" class="form-select">
            @foreach(['disponible','vendido','arrendado','reservado','pausado'] as $e)
                <option @selected(old('estado',$inmueble->estado)===$e)>{{$e}}</option>
            @endforeach
        </select></div>
        <div class="col-md-4"><label>Tipo</label><select name="id_tipo_inmueble" class="form-select">
            @foreach($tipos as $t)<option value="{{$t->id_tipo_inmueble}}" @selected(old('id_tipo_inmueble',$inmueble->id_tipo_inmueble)==$t->id_tipo_inmueble)>{{$t->nombre_tipo}}</option>@endforeach
        </select></div>
        <div class="col-md-4"><label>Negocio</label><select name="id_tipo_negocio" class="form-select">
            @foreach($negocios as $n)<option value="{{$n->id_tipo_negocio}}" @selected(old('id_tipo_negocio',$inmueble->id_tipo_negocio)==$n->id_tipo_negocio)>{{$n->nombre_tipo}}</option>@endforeach
        </select></div>
        <div class="col-md-4"><label>Asesor</label><select name="id_asesor" class="form-select"><option value="">—</option>
            @foreach($asesores as $a)<option value="{{$a->id_usuario}}" @selected(old('id_asesor',$inmueble->id_asesor)==$a->id_usuario)>{{$a->usuario}}</option>@endforeach
        </select></div>
        <div class="col-md-6"><label>Dirección</label><input name="direccion" class="form-control" value="{{ old('direccion',$inmueble->direccion) }}"></div>
        <div class="col-md-3"><label>Ciudad</label><input name="ciudad" class="form-control" value="{{ old('ciudad',$inmueble->ciudad) }}"></div>
        <div class="col-md-3"><label>Barrio</label><input name="barrio" class="form-control" value="{{ old('barrio',$inmueble->barrio) }}"></div>
        <div class="col-md-2"><label>Valor</label><input type="number" step="0.01" name="valor" class="form-control" value="{{ old('valor',$inmueble->valor) }}" required></div>
        <div class="col-md-2"><label>Admin</label><input type="number" step="0.01" name="administracion" class="form-control" value="{{ old('administracion',$inmueble->administracion) }}"></div>
        <div class="col-md-2"><label>Área m²</label><input type="number" step="0.01" name="area" class="form-control" value="{{ old('area',$inmueble->area) }}"></div>
        <div class="col-md-2"><label>Estrato</label><input type="number" name="estrato" class="form-control" value="{{ old('estrato',$inmueble->estrato) }}"></div>
        <div class="col-md-2"><label>Habit.</label><input type="number" name="habitaciones" class="form-control" value="{{ old('habitaciones',$inmueble->habitaciones) }}"></div>
        <div class="col-md-2"><label>Baños</label><input type="number" name="banos" class="form-control" value="{{ old('banos',$inmueble->banos) }}"></div>
        <div class="col-md-2"><label>Garajes</label><input type="number" name="garajes" class="form-control" value="{{ old('garajes',$inmueble->garajes) }}"></div>
        <div class="col-md-2"><label>Antigüedad</label><input type="number" name="antiguedad" class="form-control" value="{{ old('antiguedad',$inmueble->antiguedad) }}"></div>
        <div class="col-md-4"><label>Latitud</label><input type="number" step="0.0000001" name="latitud" class="form-control" value="{{ old('latitud',$inmueble->latitud) }}"></div>
        <div class="col-md-4"><label>Longitud</label><input type="number" step="0.0000001" name="longitud" class="form-control" value="{{ old('longitud',$inmueble->longitud) }}"></div>
        <div class="col-md-4"><label>Video URL</label><input name="video_url" class="form-control" value="{{ old('video_url',$inmueble->video_url) }}"></div>
        <div class="col-12"><label>Descripción</label><textarea name="descripcion" class="form-control" rows="4">{{ old('descripcion',$inmueble->descripcion) }}</textarea></div>
        <div class="col-md-6"><label>Subir imágenes</label><input type="file" name="imagenes[]" class="form-control" multiple accept="image/*"></div>
        <div class="col-md-6"><label>URLs de imágenes (una por línea)</label><textarea name="imagenes_url" class="form-control" rows="3" placeholder="https://..."></textarea></div>
        @if($inmueble->exists && $inmueble->imagenes->count())
            <div class="col-12"><label>Imágenes actuales</label><div class="d-flex gap-2 flex-wrap">
                @foreach($inmueble->imagenes as $img)<img src="{{ $img->url_imagen }}" style="width:100px;height:80px;object-fit:cover;border-radius:8px">@endforeach
            </div></div>
        @endif
        <div class="col-12"><button class="btn btn-gold"><i class="fas fa-save"></i> Guardar</button>
            <a href="{{ route('admin.propiedades.index') }}" class="btn btn-outline-dark">Cancelar</a></div>
    </form>
</div>
@endsection
