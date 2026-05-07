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
            'codigo' => 'nullable|string|min:2|max:8|regex:/^[A-Z0-9]+$/|unique:insumo,codigo',
            'tipo' => 'required|in:Tela,Hilo,Boton,Cierre,Etiqueta',
            'unidad_medida' => 'required|in:Metro,Kg,Gramo,Unidad,Rollo,Cono,Docena',
            'costo_unitario' => 'required|numeric|min:0.01',
            'stock_actual' => 'required|numeric|min:0',
            'stock_minimo' => 'required|numeric|min:0',
            'proveedor_id' => 'nullable|exists:proveedor,id',
            'estado' => 'nullable|boolean',
        ], [
            'codigo.regex' => 'El código solo admite letras mayúsculas y números.',
            'codigo.unique' => 'Ya existe un insumo con este código.',
        ]);

        $data = $request->only([
            'nombre', 'tipo', 'unidad_medida', 'costo_unitario',
            'stock_actual', 'stock_minimo', 'proveedor_id', 'estado'
        ]);
        // Código en mayúsculas, vacío como NULL
        $data['codigo'] = $request->filled('codigo') ? strtoupper(trim($request->codigo)) : null;

        $insumo = Insumo::create($data);

        return response()->json(['success' => 'Insumo creado exitosamente.', 'insumo' => $insumo]);
    }

    public function show($id)
    {
        $insumo = Insumo::with('proveedor.persona')->findOrFail($id);
        return response()->json($insumo);
    }

    public function update(Request $request, $id)
    {
        $insumo = Insumo::findOrFail($id);

        $request->validate([
            'nombre' => 'required|string|max:100',
            'codigo' => 'nullable|string|min:2|max:8|regex:/^[A-Z0-9]+$/|unique:insumo,codigo,' . $insumo->id,
            'tipo' => 'required|in:Tela,Hilo,Boton,Cierre,Etiqueta',
            'unidad_medida' => 'required|in:Metro,Kg,Gramo,Unidad,Rollo,Cono,Docena',
            'costo_unitario' => 'required|numeric|min:0.01',
            'stock_actual' => 'required|numeric|min:0',
            'stock_minimo' => 'required|numeric|min:0',
            'proveedor_id' => 'nullable|exists:proveedor,id',
            'estado' => 'nullable|boolean',
        ], [
            'codigo.regex' => 'El código solo admite letras mayúsculas y números.',
            'codigo.unique' => 'Ya existe un insumo con este código.',
        ]);

        $data = $request->only([
            'nombre', 'tipo', 'unidad_medida', 'costo_unitario',
            'stock_actual', 'stock_minimo', 'proveedor_id', 'estado'
        ]);
        // Código inmutable: solo se asigna si el insumo no tenía uno previamente
        if (empty($insumo->codigo) && $request->filled('codigo')) {
            $data['codigo'] = strtoupper(trim($request->codigo));
        }

        $insumo->update($data);

        return response()->json(['success' => 'Insumo actualizado exitosamente.']);
    }

    public function destroy($id)
    {
        $insumo = Insumo::findOrFail($id);
        $insumo->delete();
        return response()->json(['success' => 'Insumo eliminado exitosamente.']);
    }

    public function checkNombre(Request $request)
    {
        $nombre = $request->input('nombre');
        $excludeId = $request->input('exclude_id');
        if (!$nombre) return response()->json(['exists' => false]);

        $query = Insumo::whereRaw('LOWER(nombre) = ?', [strtolower($nombre)]);
        if ($excludeId) {
            $query->where('id', '!=', $excludeId);
        }
        return response()->json(['exists' => $query->exists()]);
    }

    public function reportePdf()
    {
        $insumos = Insumo::with('proveedor.persona')->get();
        $pdf = \PDF::loadView('admin.insumos.reporte_pdf', compact('insumos'))
            ->setPaper('a4', 'landscape');
        return $pdf->download('insumos_' . now()->format('Y-m-d_H-i-s') . '.pdf');
    }
}
