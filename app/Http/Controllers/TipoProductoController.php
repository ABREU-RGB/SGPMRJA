<?php

namespace App\Http\Controllers;

use App\Models\TipoProducto;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class TipoProductoController extends Controller
{
    /**
     * Listar todos los tipos de producto
     */
    public function index(Request $request): JsonResponse
    {
        $query = TipoProducto::withCount(['productos', 'atributos'])->orderBy('nombre');

        if ($request->boolean('historial')) {
            $query->onlyTrashed();
        }

        $tipos = $query->get();
        return response()->json($tipos);
    }

    /**
     * Guardar nuevo tipo de producto
     */
    public function store(Request $request): JsonResponse
    {
        $request->validate([
            'nombre' => 'required|string|max:100|unique:tipo_producto,nombre',
            'codigo_prefijo' => 'required|string|max:5|unique:tipo_producto,codigo_prefijo|alpha',
            'descripcion' => 'nullable|string|max:500',
            'precio_confeccion' => 'nullable|numeric|min:0|max:99999.99',
            'requiere_tela' => 'nullable|boolean',
            'atributos' => 'nullable|array',
            'atributos.*.id' => 'required_with:atributos|integer|exists:atributo,id',
            'atributos.*.orden' => 'required_with:atributos|integer|min:1|max:99',
        ], [
            'nombre.required' => 'El nombre es obligatorio',
            'nombre.unique' => 'Ya existe un tipo con este nombre',
            'codigo_prefijo.required' => 'El prefijo de código es obligatorio',
            'codigo_prefijo.unique' => 'Ya existe un tipo con este prefijo',
            'codigo_prefijo.alpha' => 'El prefijo solo puede contener letras',
            'codigo_prefijo.max' => 'El prefijo no puede tener más de 5 caracteres',
        ]);

        $tipo = TipoProducto::create([
            'nombre' => $request->nombre,
            'codigo_prefijo' => strtoupper($request->codigo_prefijo),
            'descripcion' => $request->descripcion,
            'precio_confeccion' => $request->input('precio_confeccion', 0),
            'requiere_tela' => $request->boolean('requiere_tela', true),
        ]);

        $this->syncAtributos($tipo, $request->input('atributos', []));

        return response()->json([
            'success' => true,
            'message' => 'Tipo de producto creado correctamente',
            'tipo' => $tipo->load('atributos'),
        ]);
    }

    /**
     * Mostrar un tipo de producto
     */
    public function show(TipoProducto $tipoProducto): JsonResponse
    {
        $tipoProducto->load([
            'atributos' => function ($q) {
                $q->orderBy('tipo_producto_atributo.orden');
            },
            'atributos.valores',
        ]);

        return response()->json($tipoProducto);
    }

    /**
     * Actualizar tipo de producto
     */
    public function update(Request $request, TipoProducto $tipoProducto): JsonResponse
    {
        $request->validate([
            'nombre' => 'required|string|max:100|unique:tipo_producto,nombre,' . $tipoProducto->id,
            'codigo_prefijo' => 'required|string|max:5|unique:tipo_producto,codigo_prefijo,' . $tipoProducto->id . '|alpha',
            'descripcion' => 'nullable|string|max:500',
            'precio_confeccion' => 'nullable|numeric|min:0|max:99999.99',
            'requiere_tela' => 'nullable|boolean',
            'atributos' => 'nullable|array',
            'atributos.*.id' => 'required_with:atributos|integer|exists:atributo,id',
            'atributos.*.orden' => 'required_with:atributos|integer|min:1|max:99',
        ]);

        $tipoProducto->update([
            'nombre' => $request->nombre,
            'codigo_prefijo' => strtoupper($request->codigo_prefijo),
            'descripcion' => $request->descripcion,
            'precio_confeccion' => $request->input('precio_confeccion', $tipoProducto->precio_confeccion),
            'requiere_tela' => $request->boolean('requiere_tela', $tipoProducto->requiere_tela),
        ]);

        $this->syncAtributos($tipoProducto, $request->input('atributos', []));

        return response()->json([
            'success' => true,
            'message' => 'Tipo de producto actualizado correctamente',
            'tipo' => $tipoProducto->load('atributos'),
        ]);
    }

    /**
     * Sincroniza la asociación tipo↔atributo respetando el orden indicado.
     * Bloquea la remoción de atributos que estén siendo usados por productos del tipo.
     */
    private function syncAtributos(TipoProducto $tipo, array $atributos): void
    {
        $sync = [];
        foreach ($atributos as $a) {
            $sync[(int) $a['id']] = [
                'es_obligatorio' => true,
                'orden' => (int) $a['orden'],
            ];
        }

        $tipo->atributos()->sync($sync);
    }

    /**
     * Eliminar tipo de producto
     */
    public function destroy(TipoProducto $tipoProducto): JsonResponse
    {
        // Verificar si tiene productos asociados
        if ($tipoProducto->productos()->count() > 0) {
            return response()->json([
                'success' => false,
                'message' => 'No se puede inhabilitar. Hay productos asociados a este tipo.',
            ], 422);
        }

        $tipoProducto->delete();

        return response()->json([
            'success' => true,
            'message' => 'Tipo de producto inhabilitado correctamente',
        ]);
    }

    /**
     * Restaurar tipo de producto inhabilitado
     */
    public function restore(int $id): JsonResponse
    {
        $tipoProducto = TipoProducto::onlyTrashed()->find($id);

        if (!$tipoProducto) {
            return response()->json([
                'success' => false,
                'message' => 'Tipo de producto no encontrado en historial.',
            ], 404);
        }

        $tipoProducto->restore();

        return response()->json([
            'success' => true,
            'message' => 'Tipo de producto restaurado correctamente',
            'tipo' => $tipoProducto,
        ]);
    }

    /**
     * Obtener el próximo código para un tipo (con preview del modelo)
     */
    public function proximoCodigo(Request $request, TipoProducto $tipoProducto): JsonResponse
    {
        $modelo = $request->query('modelo', '');

        return response()->json([
            'codigo' => $tipoProducto->proximoCodigo($modelo),
            'abreviatura' => $modelo ? TipoProducto::abreviarModelo($modelo) : 'XXX',
        ]);
    }

    public function checkNombre(Request $request)
    {
        $nombre = $request->input('nombre');
        if (!$nombre)
            return response()->json(['exists' => false]);
        $exists = TipoProducto::where('nombre', $nombre)->exists();
        return response()->json(['exists' => $exists]);
    }

    public function checkCodigoPrefijo(Request $request)
    {
        $codigo = $request->input('codigo');
        if (!$codigo)
            return response()->json(['exists' => false]);
        $exists = TipoProducto::where('codigo_prefijo', $codigo)->exists();
        return response()->json(['exists' => $exists]);
    }
}
