<?php

namespace App\Http\Controllers;

use App\Models\Logo;
use Illuminate\Http\Request;

class LogoController extends Controller
{
    /**
     * Retorna la lista completa de logos como JSON (para uso AJAX o futuras integraciones).
     */
    public function getLogos(Request $request)
    {
        $logos = Logo::orderBy('name')->get(['id', 'name', 'original_filename']);
        return response()->json($logos);
    }
}
