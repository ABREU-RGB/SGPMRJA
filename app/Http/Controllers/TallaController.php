<?php

namespace App\Http\Controllers;

use App\Models\Talla;
use Illuminate\Http\Request;

class TallaController extends Controller
{
    /**
     * Retorna la lista de tallas activas como JSON.
     * Usado por el modal de selección de talla en cotizaciones.
     */
    public function getTallas(Request $request)
    {
        $tallas = Talla::activo()
            ->orderBy('orden')
            ->orderBy('nombre')
            ->get(['id', 'nombre', 'etiqueta', 'grupo']);

        return response()->json($tallas);
    }
}
