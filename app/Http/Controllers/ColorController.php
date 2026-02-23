<?php

namespace App\Http\Controllers;

use App\Models\Color;
use Illuminate\Http\Request;

class ColorController extends Controller
{
    /**
     * Retorna la lista de colores activos como JSON, agrupados por 'grupo'.
     * Usado por el modal de selección de color en cotizaciones y pedidos.
     */
    public function getColores(Request $request)
    {
        $colores = Color::activo()
            ->orderBy('grupo')
            ->orderBy('nombre')
            ->get(['id', 'nombre', 'hex_referencial', 'grupo']);

        return response()->json($colores);
    }
}
