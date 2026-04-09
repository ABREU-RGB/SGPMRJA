<?php

namespace App\Http\Controllers;

use App\Models\Proveedor;
use App\Models\Persona;
use App\Services\ProveedorService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\DataTables;

class ProveedorController extends Controller
{
    public function __construct(
        private ProveedorService $proveedorService
    ) {
    }
    public function index(Request $request)
    {
        $historial = $request->has('historial');
        return view('admin.proveedores.index', compact('historial'));
    }

    public function getProveedores(Request $request)
    {
        if ($request->has('historial')) {
            $proveedores = Proveedor::onlyTrashed()->with('persona.telefonos', 'persona.direcciones')->get();
        } else {
            $proveedores = Proveedor::with('persona.telefonos', 'persona.direcciones')->get();
        }

        $data = $proveedores->map(function ($proveedor) {
            return [
                'id' => $proveedor->id,
                'tipo_proveedor' => $proveedor->tipo_proveedor ?? 'juridico',
                'tipo_display' => ($proveedor->tipo_proveedor ?? 'juridico') === 'natural' ? 'Natural' : 'Jurídico',
                'nombre_display' => $proveedor->nombre_completo,
                'documento_display' => $proveedor->documento,
                'telefono_display' => $proveedor->telefono_unificado,
                'email_display' => $proveedor->email_unificado,
                'estado' => $proveedor->estado,
                'trashed' => $proveedor->trashed(),
            ];
        });

        return response()->json(['data' => $data]);
    }

    public function store(Request $request)
    {
        $tipoProveedor = $request->input('tipo_proveedor', 'juridico');

        if ($tipoProveedor === 'natural') {
            $request->validate([
                'tipo_proveedor' => 'required|in:natural,juridico',
                'nombre' => 'required|string|max:100',
                'apellido' => 'required|string|max:100',
                'tipo_documento' => 'required|in:V-,E-,J-,G-',
                'documento_identidad' => 'required|string|max:20|unique:persona,documento_identidad',
                'telefono' => 'required|string|max:20',
                'email' => 'required|email|max:255|unique:persona,email',
                'direccion' => 'required|string|max:255',
                'ciudad' => 'nullable|string|max:100',
                'estado_territorial' => 'nullable|string|max:50',
            ]);

            $this->proveedorService->crearNatural($request->all());
            return response()->json(['success' => 'Proveedor natural creado exitosamente.']);
        } else {
            $request->validate([
                'tipo_proveedor' => 'required|in:natural,juridico',
                'razon_social' => 'required|string|max:100',
                'rif' => 'required|string|max:15|unique:persona,documento_identidad,NULL,id,tipo_documento,' . $this->parseRifPrefix($request->rif),
                'direccion' => 'required|string|max:200',
                'telefono' => 'required|string|max:20',
                'email' => 'required|email|max:100|unique:persona,email',
                'contacto' => 'nullable|string|max:100',
                'telefono_contacto' => 'nullable|string|max:20',
                'estado' => 'nullable|boolean',
            ]);

            $this->proveedorService->crearJuridico($request->all());
            return response()->json(['success' => 'Proveedor jurídico creado exitosamente.']);
        }
    }

    public function show($id)
    {
        $proveedor = Proveedor::with('persona.telefonos', 'persona.direcciones')->findOrFail($id);
        $persona = $proveedor->persona;
        $telefonoPrincipal = $persona ? $persona->telefonos->where('es_principal', true)->first() : null;
        $direccionPrincipal = $persona ? $persona->direcciones->where('es_principal', true)->first() : null;

        $data = [
            'id' => $proveedor->id,
            'tipo_proveedor' => $proveedor->tipo_proveedor,
            'persona_id' => $proveedor->persona_id,
            'telefono' => $telefonoPrincipal ? $telefonoPrincipal->numero : null,
            'email' => $persona ? $persona->email : null,
            'direccion' => $direccionPrincipal ? $direccionPrincipal->direccion : null,
            'contacto' => $proveedor->contacto,
            'telefono_contacto' => $proveedor->telefono_contacto,
            'nombre_display' => $proveedor->nombre_completo,
            'documento_display' => $proveedor->documento,
            'estado' => $proveedor->estado,
            'created_at' => $proveedor->created_at->format('d/m/Y H:i:s'),
            'updated_at' => $proveedor->updated_at->format('d/m/Y H:i:s'),
        ];

        if ($proveedor->esNatural() && $persona) {
            $data['nombre'] = $persona->nombre;
            $data['apellido'] = $persona->apellido;
            $data['tipo_documento'] = $persona->tipo_documento;
            $data['documento_identidad'] = $persona->documento_identidad;
            $data['ciudad'] = $direccionPrincipal ? $direccionPrincipal->ciudad : null;
            $data['estado_territorial'] = $direccionPrincipal ? $direccionPrincipal->estado : null;
        } else {
            // Para jurídico: reconstruir campos para compatibilidad con la vista
            $data['razon_social'] = $persona ? $persona->nombre : null;
            $data['rif'] = $persona ? $persona->tipo_documento . $persona->documento_identidad : null;
        }

        return response()->json($data);
    }

    public function update(Request $request, $id)
    {
        $proveedor = Proveedor::findOrFail($id);
        $tipoProveedor = $request->input('tipo_proveedor', $proveedor->tipo_proveedor ?? 'juridico');

        if ($tipoProveedor === 'natural') {
            $request->validate([
                'nombre' => 'required|string|max:100',
                'apellido' => 'required|string|max:100',
                'tipo_documento' => 'required|in:V-,E-,J-,G-',
                'documento_identidad' => 'required|string|max:20|unique:persona,documento_identidad,' . ($proveedor->persona_id ?? 0),
                'telefono' => 'required|string|max:20',
                'email' => 'required|email|max:255|unique:persona,email,' . ($proveedor->persona_id ?? 0),
                'direccion' => 'required|string|max:255',
                'ciudad' => 'nullable|string|max:100',
                'estado_territorial' => 'nullable|string|max:50',
            ]);

            $this->proveedorService->actualizarNatural($proveedor, $request->all());
            return response()->json(['success' => 'Proveedor actualizado exitosamente.']);
        } else {
            $request->validate([
                'razon_social' => 'required|string|max:100',
                'rif' => 'required|string|max:15',
                'direccion' => 'required|string|max:200',
                'telefono' => 'required|string|max:20',
                'email' => 'required|email|max:100|unique:persona,email,' . ($proveedor->persona_id ?? 0),
                'contacto' => 'nullable|string|max:100',
                'telefono_contacto' => 'nullable|string|max:20',
                'estado' => 'nullable|boolean',
                'ciudad' => 'nullable|string|max:100',
                'estado_territorial' => 'nullable|string|max:50',
            ]);

            $this->proveedorService->actualizarJuridico($proveedor, $request->all());
            return response()->json(['success' => 'Proveedor actualizado exitosamente.']);
        }
    }

    public function destroy($id)
    {
        $proveedor = Proveedor::findOrFail($id);
        $proveedor->delete(); // SoftDelete: marca deleted_at
        return response()->json(['success' => 'Proveedor inhabilitado exitosamente.']);
    }

    public function reportePdf()
    {
        $proveedores = Proveedor::with('persona.telefonos', 'persona.direcciones')->get();
        $pdf = \PDF::loadView('admin.proveedores.reporte_pdf', compact('proveedores'))
            ->setPaper('a4', 'landscape');
        return $pdf->download('proveedores_' . now()->format('Y-m-d_H-i-s') . '.pdf');
    }

    public function checkRif(Request $request)
    {
        $rif = $request->input('rif');
        if (!$rif)
            return response()->json(['exists' => false]);

        // Parsear RIF y buscar en persona
        $tipoDoc = 'J-';
        $docId = $rif;
        if (preg_match('/^(V-|J-|E-|G-)(.+)$/', $rif, $matches)) {
            $tipoDoc = $matches[1];
            $docId = $matches[2];
        }

        $exists = Persona::where('tipo_documento', $tipoDoc)
            ->where('documento_identidad', $docId)
            ->exists();
        return response()->json(['exists' => $exists]);
    }

    public function checkDocumento(Request $request)
    {
        $numero = $request->input('numero');
        if (!$numero)
            return response()->json(['exists' => false]);
        $exists = Persona::where('documento_identidad', $numero)->exists();
        return response()->json(['exists' => $exists]);
    }

    public function checkEmail(Request $request)
    {
        $email = $request->input('email');
        if (!$email)
            return response()->json(['exists' => false]);

        $exists = Persona::where('email', $email)->exists();
        return response()->json(['exists' => $exists]);
    }

    /**
     * Extraer prefijo del RIF para validación unique compuesta.
     */
    private function parseRifPrefix(string $rif): string
    {
        if (preg_match('/^(V-|J-|E-|G-)/', $rif, $matches)) {
            return $matches[1];
        }
        return 'J-';
    }
}
