<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Inmueble;
use App\Models\ImagenInmueble;
use App\Models\TipoInmueble;
use App\Models\TipoNegocio;
use App\Models\User;

class PropertyController extends Controller
{
    public function index()
    {
        $inmuebles = Inmueble::with('imagenes','tipo','negocio')->orderByDesc('id_inmueble')->paginate(15);
        return view('admin.properties.index', compact('inmuebles'));
    }

    public function create()
    {
        return view('admin.properties.form', [
            'inmueble' => new Inmueble(),
            'tipos' => TipoInmueble::all(),
            'negocios' => TipoNegocio::all(),
            'asesores' => User::where('id_tipo_usuario', 2)->get(),
        ]);
    }

    public function store(Request $r)
    {
        $data = $this->validateData($r);
        $inmueble = Inmueble::create($data);
        $this->saveImages($r, $inmueble);
        return redirect()->route('admin.propiedades.index')->with('success','Propiedad creada');
    }

    public function edit($id)
    {
        return view('admin.properties.form', [
            'inmueble' => Inmueble::with('imagenes')->findOrFail($id),
            'tipos' => TipoInmueble::all(),
            'negocios' => TipoNegocio::all(),
            'asesores' => User::where('id_tipo_usuario', 2)->get(),
        ]);
    }

    public function update(Request $r, $id)
    {
        $inmueble = Inmueble::findOrFail($id);
        $inmueble->update($this->validateData($r));
        $this->saveImages($r, $inmueble);
        return redirect()->route('admin.propiedades.index')->with('success','Propiedad actualizada');
    }

    public function destroy($id)
    {
        Inmueble::findOrFail($id)->delete();
        return back()->with('success','Propiedad eliminada');
    }

    private function validateData(Request $r): array
    {
        return $r->validate([
            'titulo' => 'required|max:150',
            'id_tipo_inmueble' => 'required|integer',
            'id_tipo_negocio' => 'required|integer',
            'direccion' => 'nullable|max:150',
            'ciudad' => 'nullable|max:100',
            'barrio' => 'nullable|max:100',
            'estrato' => 'nullable|integer',
            'valor' => 'required|numeric',
            'administracion' => 'nullable|numeric',
            'area' => 'nullable|numeric',
            'habitaciones' => 'nullable|integer',
            'banos' => 'nullable|integer',
            'garajes' => 'nullable|integer',
            'antiguedad' => 'nullable|integer',
            'descripcion' => 'nullable|string',
            'latitud' => 'nullable|numeric',
            'longitud' => 'nullable|numeric',
            'estado' => 'required|in:disponible,vendido,arrendado,reservado,pausado',
            'id_asesor' => 'nullable|integer',
            'video_url' => 'nullable|url|max:255',
        ]);
    }

    private function saveImages(Request $r, Inmueble $inmueble): void
    {
        if ($r->hasFile('imagenes')) {
            foreach ($r->file('imagenes') as $file) {
                $name = time().'_'.uniqid().'.'.$file->getClientOriginalExtension();
                $file->move(public_path('uploads'), $name);
                ImagenInmueble::create([
                    'id_inmueble' => $inmueble->id_inmueble,
                    'url_imagen' => '/uploads/'.$name,
                ]);
            }
        }
        if ($r->filled('imagenes_url')) {
            foreach (explode("\n", $r->imagenes_url) as $url) {
                $url = trim($url);
                if ($url) ImagenInmueble::create(['id_inmueble' => $inmueble->id_inmueble, 'url_imagen' => $url]);
            }
        }
    }
}
