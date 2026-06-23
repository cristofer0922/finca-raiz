<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Setting;

class SettingsController extends Controller
{
    public function index()
    {
        $settings = [
            'whatsapp_number'  => Setting::get('whatsapp_number',''),
            'whatsapp_mensaje' => Setting::get('whatsapp_mensaje',''),
        ];
        return view('admin.settings', compact('settings'));
    }

    public function update(Request $r)
    {
        $data = $r->validate([
            'whatsapp_number'  => 'required|string|max:20',
            'whatsapp_mensaje' => 'nullable|string|max:500',
        ]);
        foreach ($data as $k => $v) Setting::set($k, $v);
        return back()->with('success','Configuración guardada');
    }
}
