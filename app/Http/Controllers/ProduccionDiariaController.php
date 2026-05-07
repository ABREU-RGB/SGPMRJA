<?php

namespace App\Http\Controllers;

use App\Models\OrdenProduccion;
use App\Models\ProduccionDiaria;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Facades\DataTables;

class ProduccionDiariaController extends Controller
{
    public function index()
    {
        $empleados = \App\Models\Empleado::with('persona')
            ->whereHas('departamento', fn($q) => $q->whereRaw("LOWER(nombre) LIKE 'producc%'"))
            ->where('estado', 1)
            ->get()
            ->map(function ($empleado) {
                return (object) [
                    'id' => $empleado->id,
                    'name' => $empleado->persona->nombre_completo ?? 'Sin nombre'
                ];
            });
        $ordenes = OrdenProduccion::where('estado', 'Pendiente')
            ->where('cantidad_producida', '<', \DB::raw('cantidad_solicitada'))
            ->get();

        return view('admin.produccion.diaria.index', compact('empleados', 'ordenes'));
    }

    public function getRegistros()
    {
        $registros = ProduccionDiaria::with(['orden.producto', 'empleado.persona'])
            ->select('produccion_diaria.*');

        return DataTables::of($registros)
            ->addColumn('orden_producto', function ($registro) {
                return $registro->orden && $registro->orden->producto ? $registro->orden->producto->nombre : 'N/A';
            })
            ->addColumn('operario_nombre', function ($registro) {
                return $registro->empleado && $registro->empleado->persona
                    ? $registro->empleado->persona->nombre_completo
                    : 'N/A';
            })
            ->addColumn('fecha', function ($registro) {
                return $registro->fecha_produccion
                    ? $registro->fecha_produccion->format('d/m/Y')
                    : $registro->created_at->format('d/m/Y');
            })
            ->addColumn('actions', function ($registro) {
                $actions = '<div class="d-flex gap-2 justify-content-center">';
                if (Auth::user()->isAdmin() || Auth::user()->isSupervisor()) {
                    $actions .= '<button type="button" class="btn btn-sm btn-soft-info view-btn" data-id="' . $registro->id . '" title="Ver">';
                    $actions .= '<i class="ri-eye-fill"></i></button>';

                    $actions .= '<button type="button" class="btn btn-sm btn-soft-success edit-btn" data-id="' . $registro->id . '" title="Editar">';
                    $actions .= '<i class="ri-pencil-fill"></i></button>';

                    $actions .= '<button type="button" class="btn btn-sm btn-soft-danger remove-btn" data-id="' . $registro->id . '" title="Eliminar">';
                    $actions .= '<i class="ri-delete-bin-fill"></i></button>';
                }
                $actions .= '</div>';
                return $actions;
            })
            ->rawColumns(['actions'])
            ->make(true);
    }

    public function store(Request $request)
    {
        $request->validate([
            'orden_id' => 'required|exists:orden_produccion,id',
            'empleado_id' => 'required|exists:empleado,id',
            'fecha_produccion' => 'required|date|before_or_equal:today',
            'cantidad_producida' => 'required|numeric|min:1',
            'cantidad_defectuosa' => 'required|numeric|min:0|lte:cantidad_producida',
            'observaciones' => 'nullable|string|max:500'
        ], [
            'cantidad_defectuosa.lte' => 'La cantidad defectuosa no puede superar la cantidad producida.',
        ]);

        $orden = OrdenProduccion::find($request->orden_id);

        if (in_array($orden->estado, ['Finalizado', 'Cancelado'])) {
            return response()->json([
                'error' => "La orden ya está en estado \"{$orden->estado}\" y no puede recibir más avances."
            ], 422);
        }

        $restante = $orden->cantidad_solicitada - $orden->cantidad_producida;
        if ($request->cantidad_producida > $restante) {
            return response()->json([
                'error' => "La cantidad ingresada ({$request->cantidad_producida}) supera las {$restante} piezas restantes de la orden."
            ], 422);
        }

        try {
            $registro = ProduccionDiaria::create([
                'orden_id' => $request->orden_id,
                'empleado_id' => $request->empleado_id,
                'fecha_produccion' => $request->fecha_produccion,
                'cantidad_producida' => $request->cantidad_producida,
                'cantidad_defectuosa' => $request->cantidad_defectuosa,
                'observaciones' => $request->observaciones
            ]);

            // Actualizar cantidad producida y estado de la orden
            $orden->cantidad_producida += $request->cantidad_producida;
            if ($orden->cantidad_producida >= $orden->cantidad_solicitada) {
                $orden->estado = 'Finalizado';
                $orden->fecha_fin_real = now()->toDateString();
            } elseif ($orden->estado === 'Pendiente') {
                $orden->estado = 'En Proceso';
            }
            $orden->save();

            return response()->json([
                'success' => 'Registro de producción creado exitosamente'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Error al crear el registro de producción'
            ], 500);
        }
    }

    public function show($id)
    {
        $registro = ProduccionDiaria::with(['orden.producto', 'empleado.persona'])->findOrFail($id);
        return response()->json($registro);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'fecha_produccion' => 'required|date|before_or_equal:today',
            'cantidad_producida' => 'required|numeric|min:1',
            'cantidad_defectuosa' => 'required|numeric|min:0|lte:cantidad_producida',
            'observaciones' => 'nullable|string|max:500'
        ], [
            'cantidad_defectuosa.lte' => 'La cantidad defectuosa no puede superar la cantidad producida.',
        ]);

        try {
            $registro = ProduccionDiaria::findOrFail($id);

            // Restar la cantidad anterior de la orden
            $orden = $registro->orden;
            $orden->cantidad_producida -= $registro->cantidad_producida;

            // Actualizar el registro
            $registro->update([
                'fecha_produccion' => $request->fecha_produccion,
                'cantidad_producida' => $request->cantidad_producida,
                'cantidad_defectuosa' => $request->cantidad_defectuosa,
                'observaciones' => $request->observaciones
            ]);

            // Actualizar la nueva cantidad en la orden
            $orden->cantidad_producida += $request->cantidad_producida;
            if ($orden->cantidad_producida >= $orden->cantidad_solicitada) {
                $orden->estado = 'Finalizado';
                if (is_null($orden->fecha_fin_real)) {
                    $orden->fecha_fin_real = now()->toDateString();
                }
            } else {
                $orden->estado = 'En Proceso';
                $orden->fecha_fin_real = null;
            }
            $orden->save();

            return response()->json([
                'success' => 'Registro de producción actualizado exitosamente'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Error al actualizar el registro de producción'
            ], 500);
        }
    }

    public function destroy($id)
    {
        try {
            $registro = ProduccionDiaria::findOrFail($id);

            // Actualizar cantidad producida en la orden
            $orden = $registro->orden;
            $orden->cantidad_producida -= $registro->cantidad_producida;
            if ($orden->cantidad_producida < $orden->cantidad_solicitada) {
                $orden->estado = 'En Proceso';
            }
            $orden->save();

            $registro->delete();

            return response()->json([
                'success' => 'Registro de producción eliminado exitosamente'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Error al eliminar el registro de producción'
            ], 500);
        }
    }
}
