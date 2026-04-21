<?php

namespace App\Http\Controllers;

use App\Models\Departamento;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class DepartamentoController extends Controller
{
    /**
     * Listar departamentos (activos o historial) con conteo de cargos y empleados.
     */
    public function index(Request $request): JsonResponse
    {
        $query = Departamento::withCount(['cargos', 'empleados'])->orderBy('nombre');

        if ($request->boolean('historial')) {
            $query->onlyTrashed();
        }

        return response()->json($query->get());
    }

    public function store(Request $request): JsonResponse
    {
        $request->validate([
            'nombre' => 'required|string|min:3|max:100|unique:departamento,nombre',
        ], [
            'nombre.required' => 'El nombre es obligatorio.',
            'nombre.min'      => 'El nombre debe tener al menos 3 caracteres.',
            'nombre.unique'   => 'Ya existe un departamento con este nombre.',
        ]);

        $departamento = Departamento::create([
            'nombre' => trim($request->nombre),
            'activo' => true,
        ]);

        return response()->json([
            'success'      => true,
            'message'      => 'Departamento creado correctamente.',
            'departamento' => $departamento,
        ]);
    }

    public function show(Departamento $departamento): JsonResponse
    {
        return response()->json($departamento);
    }

    public function update(Request $request, Departamento $departamento): JsonResponse
    {
        $request->validate([
            'nombre' => 'required|string|min:3|max:100|unique:departamento,nombre,' . $departamento->id,
        ], [
            'nombre.required' => 'El nombre es obligatorio.',
            'nombre.min'      => 'El nombre debe tener al menos 3 caracteres.',
            'nombre.unique'   => 'Ya existe un departamento con este nombre.',
        ]);

        $departamento->update(['nombre' => trim($request->nombre)]);

        return response()->json([
            'success'      => true,
            'message'      => 'Departamento actualizado correctamente.',
            'departamento' => $departamento,
        ]);
    }

    public function destroy(Departamento $departamento): JsonResponse
    {
        if ($departamento->cargos()->count() > 0) {
            return response()->json([
                'success' => false,
                'message' => 'No se puede inhabilitar: el departamento tiene cargos asociados.',
            ], 422);
        }

        if ($departamento->empleados()->count() > 0) {
            return response()->json([
                'success' => false,
                'message' => 'No se puede inhabilitar: el departamento tiene empleados asociados.',
            ], 422);
        }

        $departamento->delete();

        return response()->json([
            'success' => true,
            'message' => 'Departamento inhabilitado correctamente.',
        ]);
    }

    public function restore(int $id): JsonResponse
    {
        $departamento = Departamento::onlyTrashed()->find($id);

        if (!$departamento) {
            return response()->json([
                'success' => false,
                'message' => 'Departamento no encontrado en historial.',
            ], 404);
        }

        $departamento->restore();

        return response()->json([
            'success'      => true,
            'message'      => 'Departamento restaurado correctamente.',
            'departamento' => $departamento,
        ]);
    }

    public function checkNombre(Request $request): JsonResponse
    {
        $nombre    = trim((string) $request->input('nombre'));
        $excludeId = $request->input('exclude_id');

        if ($nombre === '') {
            return response()->json(['exists' => false]);
        }

        $query = Departamento::whereRaw('LOWER(nombre) = ?', [strtolower($nombre)]);
        if ($excludeId) {
            $query->where('id', '!=', $excludeId);
        }

        return response()->json(['exists' => $query->exists()]);
    }
}
