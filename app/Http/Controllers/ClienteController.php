<?php

namespace App\Http\Controllers;

use App\Models\Cliente;
use App\Models\Persona;
use App\Models\Telefono;
use App\Models\Direccion;
use App\Http\Requests\StoreClienteRequest;
use App\Http\Requests\UpdateClienteRequest;
use App\Services\ClienteService;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\DB;


class ClienteController extends Controller
{
    public function __construct(
        private ClienteService $clienteService
    ) {
    }

    public function index(Request $request)
    {
        $historial = $request->has('historial');
        return view('admin.clientes.index', compact('historial'));
    }

    public function getClientes(Request $request)
    {
        // ── Base query con relaciones ──
        $query = Cliente::with(['persona.telefonos', 'persona.direcciones']);

        // ══════════════════════════════════════════════════════════
        // FILTROS AVANZADOS — Server-Side (Patrón Maestro S-07)
        // Cada filtro se aplica solo si el frontend envía un valor.
        // Para replicar en otros módulos: copiar este bloque y
        // ajustar los nombres de columna/accessor según el modelo.
        // ══════════════════════════════════════════════════════════

        // Filtro: Tipo de Cliente (natural, juridico, gubernamental)
        if ($request->filled('filter_tipo_cliente')) {
            $query->where('tipo_cliente', $request->input('filter_tipo_cliente'));
        }

        // Filtro: Estatus (1 = activo, 0 = inactivo/trashed)
        if ($request->filled('filter_estatus')) {
            $estatus = $request->input('filter_estatus');
            if ($estatus === '0') {
                // Inactivo: incluir registros en papelera (SoftDeletes)
                $query->onlyTrashed();
            }
            // Si estatus es '1' (activo), no se necesita filtro adicional
            // porque el query base ya excluye trashed por defecto.
        }

        // Filtro: Estado Territorial
        if ($request->filled('filter_estado_territorial')) {
            $estado = $request->input('filter_estado_territorial');
            $query->whereHas('persona.direcciones', function ($q) use ($estado) {
                $q->where('estado', $estado);
            });
        }

        // Filtro: Documento (búsqueda parcial por cédula/RIF)
        if ($request->filled('filter_documento')) {
            $doc = $request->input('filter_documento');
            $query->whereHas('persona', function ($q) use ($doc) {
                $q->where(DB::raw("CONCAT(tipo_documento, documento_identidad)"), 'LIKE', "%{$doc}%");
            });
        }

        return DataTables::of($query)
            ->addColumn('nombre', fn($c) => $c->nombre ?? 'N/A')
            ->addColumn('apellido', fn($c) => $c->apellido ?? '')
            ->addColumn('tipo_cliente', fn($c) => $c->tipo_cliente)
            ->addColumn('email', fn($c) => $c->email)
            ->addColumn('telefono', fn($c) => $c->telefono)
            ->addColumn('documento', fn($c) => $c->documento)
            ->addColumn('direccion', fn($c) => $c->direccion)
            ->addColumn('estado_territorial', fn($c) => $c->estado_territorial)
            ->addColumn('ciudad', fn($c) => $c->ciudad)
            ->addColumn('estatus', fn($c) => $c->estatus)
            ->addColumn('created_at', fn($c) => $c->created_at ? $c->created_at->format('d/m/Y H:i') : null)
            ->addColumn('trashed', fn($c) => $c->trashed())
            ->make(true);
    }

    public function store(StoreClienteRequest $request)
    {
        $clienteId = $this->clienteService->crear($request->validated());

        return response()->json([
            'message' => 'Cliente creado exitosamente.',
            'cliente_id' => $clienteId
        ]);
    }

    public function edit($id)
    {
        $cliente = Cliente::with(['persona.telefonos', 'persona.direcciones'])->findOrFail($id);

        // Obtener teléfono y dirección principal
        $telefonoPrincipal = $cliente->telefono;
        $direccionPrincipal = $cliente->persona ? $cliente->persona->direccion_principal : null;

        // Formatear respuesta para compatibilidad con el frontend existente
        return response()->json([
            'id' => $cliente->id,
            'persona_id' => $cliente->persona_id,
            'nombre' => $cliente->persona ? $cliente->persona->nombre : '',
            'apellido' => $cliente->persona ? $cliente->persona->apellido : '',
            'tipo_cliente' => $cliente->tipo_cliente,
            'email' => $cliente->persona ? $cliente->persona->email : '',
            'telefono' => $telefonoPrincipal ?? '',
            'documento' => $cliente->persona ? ($cliente->persona->tipo_documento . $cliente->persona->documento_identidad) : '',
            'direccion' => $direccionPrincipal ? $direccionPrincipal->direccion : '',
            'estado_territorial' => $direccionPrincipal ? $direccionPrincipal->estado : '',
            'ciudad' => $direccionPrincipal ? $direccionPrincipal->ciudad : '',
            'estatus' => $cliente->estatus,
            // Datos adicionales para UI de múltiples teléfonos/direcciones
            'telefonos' => $cliente->persona ? $cliente->persona->telefonos : [],
            'direcciones' => $cliente->persona ? $cliente->persona->direcciones : [],
        ]);
    }

    public function update(UpdateClienteRequest $request, $id)
    {
        $cliente = Cliente::with(['persona.telefonos', 'persona.direcciones'])->findOrFail($id);

        $this->clienteService->actualizar($cliente, $request->validated());

        return response()->json(['message' => 'Cliente actualizado exitosamente.']);
    }

    public function destroy($id)
    {
        $cliente = Cliente::find($id);

        if (!$cliente) {
            return response()->json(['error' => 'Cliente no encontrado.'], 404);
        }

        // Verificar si tiene cotizaciones asociadas
        $cotizacionesCount = $cliente->cotizaciones()->count();

        $cliente->delete(); // SoftDelete: marca deleted_at

        \Log::warning('Cliente inhabilitado (soft delete)', [
            'cliente_id' => $id,
            'cotizaciones_count' => $cotizacionesCount,
            'user_id' => auth()->id(),
        ]);

        if ($cotizacionesCount > 0) {
            return response()->json([
                'message' => 'Cliente inhabilitado exitosamente.',
                'warning' => 'Este cliente tenía ' . $cotizacionesCount . ' cotización(es). Los registros históricos se mantienen.'
            ]);
        }

        return response()->json(['message' => 'Cliente inhabilitado exitosamente.']);
    }

    /**
     * Restaurar un cliente inhabilitado (soft-deleted).
     */
    public function restore($id)
    {
        $cliente = Cliente::onlyTrashed()->findOrFail($id);
        $cliente->restore();

        \Log::info('Cliente restaurado', [
            'cliente_id' => $id,
            'user_id' => auth()->id(),
        ]);

        return response()->json(['message' => 'Cliente restaurado exitosamente.']);
    }

    public function show($id)
    {
        $cliente = Cliente::with(['persona.telefonos', 'persona.direcciones'])->findOrFail($id);
        return response()->json([
            'id' => $cliente->id,
            'nombre' => $cliente->nombre ?? 'N/A',
            'apellido' => $cliente->apellido ?? '',
            'tipo_cliente' => $cliente->tipo_cliente,
            'email' => $cliente->email,
            'telefono' => $cliente->telefono,
            'documento' => $cliente->documento,
            'direccion' => $cliente->direccion,
            'estado_territorial' => $cliente->estado_territorial,
            'ciudad' => $cliente->ciudad,
            'estatus' => $cliente->estatus,
            'created_at' => $cliente->created_at ? $cliente->created_at->format('d/m/Y H:i:s') : null,
            'updated_at' => $cliente->updated_at ? $cliente->updated_at->format('d/m/Y H:i:s') : null
        ]);
    }

    /**
     * Buscar clientes por documento de identidad (para autocompletado AJAX)
     */
    public function searchAjax(Request $request)
    {
        $query = $request->input('q');
        $escaped = str_replace(['%', '_'], ['\\%', '\\_'], $query);
        $clientes = Cliente::with(['persona.telefonos', 'persona.direcciones'])
            ->whereHas('persona', function ($q) use ($escaped) {
                // Buscar por documento_identidad (solo el número, sin prefijo)
                $q->where('documento_identidad', 'LIKE', "%{$escaped}%");
            })
            ->where('estatus', 1)
            ->limit(10)
            ->get();

        // Formatear respuesta usando accessors del modelo Cliente
        $resultado = $clientes->map(function ($cliente) {
            return [
                'id' => $cliente->id,
                'nombre' => $cliente->nombre ?? 'N/A',
                'apellido' => $cliente->apellido ?? '',
                'email' => $cliente->email,
                'telefono' => $cliente->telefono, // Usa accessor
                'documento' => $cliente->documento,
            ];
        });

        return response()->json($resultado);
    }

    /**
     * Verificar si un documento ya existe (AJAX)
     */
    public function checkDocumento(Request $request)
    {
        $numero = $request->input('numero');
        if (!$numero) {
            return response()->json(['exists' => false]);
        }

        // Buscar coincidencia exacta en la tabla 'persona'
        $exists = \App\Models\Persona::where('documento_identidad', $numero)->exists();

        return response()->json(['exists' => $exists]);
    }

    /**
     * Exportar reporte de clientes en PDF
     */
    public function exportarPDF()
    {
        $clientes = Cliente::with('persona')->get();
        $pdf = Pdf::loadView('admin.clientes.reporte_pdf', compact('clientes'))->setPaper('a4', 'landscape');
        return $pdf->download('reporte_clientes_' . now()->format('Ymd_His') . '.pdf');
    }

    public function checkEmail(Request $request)
    {
        $email = $request->input('email');
        if (!$email)
            return response()->json(['exists' => false]);

        $query = \App\Models\Persona::where('email', $email);

        $excludeClienteId = $request->input('exclude_id');
        if ($excludeClienteId) {
            $cliente = Cliente::find($excludeClienteId);
            if ($cliente && $cliente->persona_id) {
                $query->where('id', '!=', $cliente->persona_id);
            }
        }

        return response()->json(['exists' => $query->exists()]);
    }
}
