<?php

namespace App\Http\Controllers;

use App\Models\Insumo;
use App\Models\Proveedor;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class InsumoController extends Controller
{
    public function index()
    {
        $proveedores = Proveedor::with('persona')->where('estado', true)->get();
        return view('admin.insumos.index', compact('proveedores'));
    }

    public function getInsumos()
    {
        $insumos = Insumo::with('proveedor.persona');
        return DataTables::of($insumos)
            ->addColumn('proveedor_nombre', function ($insumo) {
                return $insumo->proveedor ? $insumo->proveedor->nombre_completo : 'Sin proveedor';
            })
            ->addColumn('stock_status', function ($insumo) {
                if ($insumo->stock_actual <= $insumo->stock_minimo) {
                    return 'bajo';
                } elseif ($insumo->stock_actual <= ($insumo->stock_minimo * 1.5)) {
                    return 'medio';
                } else {
                    return 'normal';
                }
            })
            ->make(true);
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:100',
            'tipo' => 'required|in:Tela,Hilo,Boton,Cierre,Etiqueta',
            'unidad_medida' => 'required|in:Metro,Kg,Gramo,Unidad,Rollo,Cono,Docena',
            'costo_unitario' => 'required|numeric|min:0.01',
            'stock_actual' => 'required|numeric|min:0',
            'stock_minimo' => 'required|numeric|min:0',
            'proveedor_id' => 'nullable|exists:proveedor,id',
            'estado' => 'nullable|boolean',
        ]);

        $insumo = Insumo::create($request->only([
            'nombre',
            'tipo',
            'unidad_medida',
            'costo_unitario',
            'stock_actual',
            'stock_minimo',
            'proveedor_id',
            'estado'
        ]));

        return response()->json(['success' => 'Insumo creado exitosamente.', 'insumo' => $insumo]);
    }

    public function show($id)
    {
        $insumo = Insumo::with('proveedor.persona')->findOrFail($id);
        return response()->json($insumo);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nombre' => 'required|string|max:100',
            'tipo' => 'required|in:Tela,Hilo,Boton,Cierre,Etiqueta',
            'unidad_medida' => 'required|in:Metro,Kg,Gramo,Unidad,Rollo,Cono,Docena',
            'costo_unitario' => 'required|numeric|min:0.01',
            'stock_actual' => 'required|numeric|min:0',
            'stock_minimo' => 'required|numeric|min:0',
            'proveedor_id' => 'nullable|exists:proveedor,id',
            'estado' => 'nullable|boolean',
        ]);

        $insumo = Insumo::findOrFail($id);
        $insumo->update($request->only([
            'nombre',
            'tipo',
            'unidad_medida',
            'costo_unitario',
            'stock_actual',
            'stock_minimo',
            'proveedor_id',
            'estado'
        ]));

        return response()->json(['success' => 'Insumo actualizado exitosamente.']);
    }

    public function destroy($id)
    {
        $insumo = Insumo::findOrFail($id);
        $insumo->delete();
        return response()->json(['success' => 'Insumo eliminado exitosamente.']);
    }

    public function reportePdf()
    {
        $insumos = Insumo::with('proveedor.persona')->get();
        $pdf = \PDF::loadView('admin.insumos.reporte_pdf', compact('insumos'))
            ->setPaper('a4', 'landscape');
        return $pdf->download('insumos_' . now()->format('Y-m-d_H-i-s') . '.pdf');
    }
}
