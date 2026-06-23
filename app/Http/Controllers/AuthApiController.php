<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class AuthApiController extends Controller
{    
    
    public function login(Request $r)
{
    return response()->json([
        'funciona' => true,
        'correo' => $r->correo,
        'contrasena' => $r->contrasena
    ]);
}
}
