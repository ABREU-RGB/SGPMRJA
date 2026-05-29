<?php

namespace App\Http\Controllers;

use App\Models\Cargo;
use App\Models\Departamento;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CargoController extends Controller
{
    /**
     * Listar cargos (activos o historial) con su departamento y conteo de empleados.
     * Filtros: ?historial=true | ?departamento_id=N
     * Devuelve la vista de catálogo en peticiones de navegador y JSON en AJAX.
     */
    public function index(Request $request)
    {
        $historial = $request->boolean('historial');

        if ($request->wantsJson() || $request->ajax()) {
            $query = Cargo::with('departamento')
                ->withCount('empleados')
                ->orderBy('nombre');

            if ($historial) {
                $query->onlyTrashed();
            }

            if ($request->filled('departamento_id')) {
                $query->where('departamento_id', $request->departamento_id);
            }

            return response()->json($query->get());
        }

        return view('admin.cargos.index', [
            'historial'     => $historial,
            'departamentos' => Departamento::orderBy('nombre')->pluck('nombre', 'id'),
        ]);
    }

    public function store(Request $request): JsonResponse
    {
        $request->validate([
            'nombre'          => 'required|string|min:3|max:100',
            'departamento_id' => 'required|exists:departamento,id',
        ], [
            'nombre.required'          => 'El nombre es obligatorio.',
            'nombre.min'               => 'El nombre debe tener al menos 3 caracteres.',
            'departamento_id.required' => 'Debe seleccionar un departamento.',
            'departamento_id.exists'   => 'El departamento no es válido.',
        ]);

        $nombre         = trim($request->nombre);
        $departamentoId = $request->departamento_id;

        $existe = Cargo::whereRaw('LOWER(nombre) = ?', [strtolower($nombre)])
            ->where('departamento_id', $departamentoId)
            ->exists();

        if ($existe) {
            return response()->json([
                'success' => false,
                'message' => 'Ya existe un cargo con este nombre en el departamento seleccionado.',
            ], 422);
        }

        $cargo = Cargo::create([
            'nombre'          => $nombre,
            'departamento_id' => $departamentoId,
            'activo'          => true,
        ]);

        $cargo->load('departamento');

        return response()->json([
            'success' => true,
            'message' => 'Cargo creado correctamente.',
            'cargo'   => $cargo,
        ]);
    }

    public function show(Cargo $cargo): JsonResponse
    {
        $cargo->load('departamento');
        return response()->json($cargo);
    }

    public function update(Request $request, Cargo $cargo): JsonResponse
    {
        $request->validate([
            'nombre'          => 'required|string|min:3|max:100',
            'departamento_id' => 'required|exists:departamento,id',
        ], [
            'nombre.required'          => 'El nombre es obligatorio.',
            'nombre.min'               => 'El nombre debe tener al menos 3 caracteres.',
            'departamento_id.required' => 'Debe seleccionar un departamento.',
            'departamento_id.exists'   => 'El departamento no es válido.',
        ]);

        $nombre         = trim($request->nombre);
        $departamentoId = $request->departamento_id;

        $existe = Cargo::whereRaw('LOWER(nombre) = ?', [strtolower($nombre)])
            ->where('departamento_id', $departamentoId)
            ->where('id', '!=', $cargo->id)
            ->exists();

        if ($existe) {
            return response()->json([
                'success' => false,
                'message' => 'Ya existe un cargo con este nombre en el departamento seleccionado.',
            ], 422);
        }

        $cargo->update([
            'nombre'          => $nombre,
            'departamento_id' => $departamentoId,
        ]);

        $cargo->load('departamento');

        return response()->json([
            'success' => true,
            'message' => 'Cargo actualizado correctamente.',
            'cargo'   => $cargo,
        ]);
    }

    public function destroy(Cargo $cargo): JsonResponse
    {
        if ($cargo->empleados()->count() > 0) {
            return response()->json([
                'success' => false,
                'message' => 'No se puede inhabilitar: el cargo tiene empleados asociados.',
            ], 422);
        }

        $cargo->delete();

        return response()->json([
            'success' => true,
            'message' => 'Cargo inhabilitado correctamente.',
        ]);
    }

    public function restore(int $id): JsonResponse
    {
        $cargo = Cargo::onlyTrashed()->find($id);

        if (!$cargo) {
            return response()->json([
                'success' => false,
                'message' => 'Cargo no encontrado en historial.',
            ], 404);
        }

        $cargo->restore();
        $cargo->load('departamento');

        return response()->json([
            'success' => true,
            'message' => 'Cargo restaurado correctamente.',
            'cargo'   => $cargo,
        ]);
    }

    public function checkNombre(Request $request): JsonResponse
    {
        $nombre         = trim((string) $request->input('nombre'));
        $departamentoId = $request->input('departamento_id');
        $excludeId      = $request->input('exclude_id');

        if ($nombre === '' || !$departamentoId) {
            return response()->json(['exists' => false]);
        }

        $query = Cargo::whereRaw('LOWER(nombre) = ?', [strtolower($nombre)])
            ->where('departamento_id', $departamentoId);

        if ($excludeId) {
            $query->where('id', '!=', $excludeId);
        }

        return response()->json(['exists' => $query->exists()]);
    }
}
