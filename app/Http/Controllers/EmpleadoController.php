<?php

namespace App\Http\Controllers;

use App\Models\Cliente;
use App\Models\Empleado;
use App\Models\Persona;
use App\Services\EmpleadoService;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\DB;
use PDF;

class EmpleadoController extends Controller
{
    public function __construct(
        private EmpleadoService $empleadoService
    ) {
    }
    public function index()
    {
        // Departamentos dinámicos para el select del modal
        $departamentos = Empleado::whereNotNull('departamento')
            ->pluck('departamento')
            ->unique()
            ->mapWithKeys(fn($d) => [$d => $d])
            ->toArray();

        return view('admin.empleados.index', compact('departamentos'));
    }

    /**
     * Mostrar formulario de creación de empleado
     */
    public function create()
    {
        // Obtener departamentos existentes para el dropdown
        $departamentos = Empleado::whereNotNull('departamento')
            ->pluck('departamento')
            ->unique()
            ->mapWithKeys(fn($d) => [$d => $d])
            ->toArray();

        return view('admin.empleados.create', compact('departamentos'));
    }

    public function getEmpleados()
    {
        // Cargar persona con telefonos y direcciones normalizadas
        $empleados = Empleado::with(['persona.telefonos', 'persona.direcciones'])->get();

        return DataTables::of($empleados)
            ->addColumn('nombre_completo', function ($empleado) {
                return $empleado->persona ? $empleado->persona->nombre_completo : 'N/A';
            })
            ->addColumn('documento', function ($empleado) {
                return $empleado->documento ?? 'N/A'; // Usa accessor
            })
            ->addColumn('email', function ($empleado) {
                return $empleado->email ?? 'N/A'; // Usa accessor
            })
            ->addColumn('telefono', function ($empleado) {
                return $empleado->telefono ?? 'N/A'; // Usa accessor que obtiene telefono_principal
            })
            ->addColumn('actions', function ($empleado) {
                return '
                    <div class="d-flex gap-2 justify-content-center">
                        <button class="btn btn-sm btn-soft-info view-btn" data-id="' . $empleado->id . '" title="Ver"><i class="ri-eye-fill"></i></button>
                        <button class="btn btn-sm btn-soft-success edit-btn" data-id="' . $empleado->id . '" title="Editar"><i class="ri-pencil-fill"></i></button>
                        <button class="btn btn-sm btn-soft-danger remove-btn" data-id="' . $empleado->id . '" title="Eliminar"><i class="ri-delete-bin-fill"></i></button>
                    </div>
                ';
            })
            ->rawColumns(['actions'])
            ->make(true);
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|min:2|max:100|regex:/^[a-zA-ZáéíóúÁÉÍÓÚñÑ\s]+$/',
            'apellido' => 'required|string|min:2|max:100|regex:/^[a-zA-ZáéíóúÁÉÍÓÚñÑ\s]+$/',
            'documento_identidad' => 'required|string|min:6|max:15|regex:/^[0-9]+$/',
            'tipo_documento' => 'required|in:V-,E-,J-,G-',
            'email' => 'nullable|email:rfc,dns|max:255',
            'telefono' => 'nullable|string|regex:/^[0-9]{4}-[0-9]{7}$/',
            'direccion' => 'nullable|string|max:500',
            'ciudad' => 'nullable|string|max:100',
            'estado_geografico' => 'nullable|string|max:100',
            'fecha_nacimiento' => 'nullable|date|before:-18 years',
            'genero' => 'nullable|in:M,F',
            'codigo_empleado' => 'nullable|string|max:50|unique:empleado,codigo_empleado',
            'fecha_ingreso' => 'required|date|before_or_equal:today',
            'cargo' => 'required|string|min:3|max:100',
            'departamento' => 'required|string|in:Administracion,Produccion',
            'estado' => 'required|boolean',
        ], [
            // Mensajes personalizados
            'nombre.required' => 'El nombre es obligatorio',
            'nombre.min' => 'El nombre debe tener al menos 2 caracteres',
            'nombre.regex' => 'El nombre solo puede contener letras y espacios',
            'apellido.required' => 'El apellido es obligatorio',
            'apellido.min' => 'El apellido debe tener al menos 2 caracteres',
            'apellido.regex' => 'El apellido solo puede contener letras y espacios',
            'documento_identidad.required' => 'El documento de identidad es obligatorio',
            'documento_identidad.min' => 'El documento debe tener al menos 6 dígitos',
            'documento_identidad.regex' => 'El documento solo puede contener números',
            'documento_identidad.unique' => 'Este documento ya está registrado',
            'tipo_documento.required' => 'Debe seleccionar el tipo de documento',
            'email.email' => 'El email debe ser una dirección válida',
            'email.unique' => 'Este email ya está registrado',
            'telefono.regex' => 'El teléfono debe tener el formato 0424-1234567',
            'fecha_nacimiento.before' => 'El empleado debe ser mayor de 18 años',
            'fecha_ingreso.required' => 'La fecha de ingreso es obligatoria',
            'fecha_ingreso.before_or_equal' => 'La fecha de ingreso no puede ser futura',
            'cargo.required' => 'El cargo es obligatorio',
            'cargo.min' => 'El cargo debe tener al menos 3 caracteres',
            'departamento.required' => 'El departamento es obligatorio',
            'departamento.in' => 'Debe seleccionar un departamento válido (Administracion o Produccion)',
            'estado.required' => 'Debe seleccionar el estado del empleado',
        ]);

        $this->empleadoService->crear($request->all());

        return response()->json(['message' => 'Empleado creado exitosamente.']);
    }

    public function show($id)
    {
        $empleado = Empleado::with(['persona.telefonos', 'persona.direcciones'])->findOrFail($id);

        // Convertir a array y agregar campos normalizados
        $data = $empleado->toArray();
        $data['telefono'] = $empleado->telefono;
        $data['direccion'] = $empleado->direccion;
        $data['ciudad'] = $empleado->ciudad;

        return response()->json($data);
    }

    public function edit($id)
    {
        $empleado = Empleado::with(['persona.telefonos', 'persona.direcciones'])->findOrFail($id);

        // Formatear fechas para los campos input type="date"
        $data = $empleado->toArray();
        $data['persona']['fecha_nacimiento'] = $empleado->persona->fecha_nacimiento
            ? $empleado->persona->fecha_nacimiento->format('Y-m-d')
            : null;
        $data['fecha_ingreso'] = $empleado->fecha_ingreso
            ? \Carbon\Carbon::parse($empleado->fecha_ingreso)->format('Y-m-d')
            : null;

        // Agregar datos de telefono/direccion usando accessors (tablas normalizadas)
        $data['telefono'] = $empleado->telefono;
        $data['direccion'] = $empleado->direccion;
        $data['ciudad'] = $empleado->ciudad;

        return response()->json($data);
    }

    public function update(Request $request, $id)
    {
        $empleado = Empleado::findOrFail($id);
        $persona = $empleado->persona;

        $request->validate([
            'nombre' => 'required|string|min:2|max:100|regex:/^[a-zA-ZáéíóúÁÉÍÓÚñÑ\s]+$/',
            'apellido' => 'required|string|min:2|max:100|regex:/^[a-zA-ZáéíóúÁÉÍÓÚñÑ\s]+$/',
            'documento_identidad' => 'required|string|min:6|max:15|regex:/^[0-9]+$/|unique:persona,documento_identidad,' . $persona->id,
            'tipo_documento' => 'required|in:V-,E-,J-,G-',
            'email' => 'nullable|email:rfc,dns|max:255|unique:persona,email,' . $persona->id,
            'telefono' => 'nullable|string|regex:/^[0-9]{4}-[0-9]{7}$/',
            'direccion' => 'nullable|string|max:500',
            'ciudad' => 'nullable|string|max:100',
            'estado_geografico' => 'nullable|string|max:100',
            'fecha_nacimiento' => 'nullable|date|before:-18 years',
            'genero' => 'nullable|in:M,F',
            'codigo_empleado' => 'required|string|max:50|unique:empleado,codigo_empleado,' . $id,
            'fecha_ingreso' => 'required|date|before_or_equal:today',
            'cargo' => 'required|string|min:3|max:100',
            'departamento' => 'required|string|in:Administracion,Produccion',
            'estado' => 'required|boolean',
        ], [
            // Mensajes personalizados
            'nombre.required' => 'El nombre es obligatorio',
            'nombre.min' => 'El nombre debe tener al menos 2 caracteres',
            'nombre.regex' => 'El nombre solo puede contener letras y espacios',
            'apellido.required' => 'El apellido es obligatorio',
            'apellido.min' => 'El apellido debe tener al menos 2 caracteres',
            'apellido.regex' => 'El apellido solo puede contener letras y espacios',
            'documento_identidad.required' => 'El documento de identidad es obligatorio',
            'documento_identidad.min' => 'El documento debe tener al menos 6 dígitos',
            'documento_identidad.regex' => 'El documento solo puede contener números',
            'documento_identidad.unique' => 'Este documento ya está registrado',
            'tipo_documento.required' => 'Debe seleccionar el tipo de documento',
            'email.email' => 'El email debe ser una dirección válida',
            'email.unique' => 'Este email ya está registrado',
            'telefono.regex' => 'El teléfono debe tener el formato 0424-1234567',
            'fecha_nacimiento.before' => 'El empleado debe ser mayor de 18 años',
            'fecha_ingreso.required' => 'La fecha de ingreso es obligatoria',
            'fecha_ingreso.before_or_equal' => 'La fecha de ingreso no puede ser futura',
            'cargo.required' => 'El cargo es obligatorio',
            'cargo.min' => 'El cargo debe tener al menos 3 caracteres',
            'departamento.required' => 'El departamento es obligatorio',
            'departamento.in' => 'Debe seleccionar un departamento válido (Administracion o Produccion)',
            'estado.required' => 'Debe seleccionar el estado del empleado',
        ]);

        $this->empleadoService->actualizar($empleado, $request->all());

        return response()->json(['message' => 'Empleado actualizado exitosamente.']);
    }

    public function destroy($id)
    {
        $empleado = Empleado::findOrFail($id);
        $empleado->delete();
        return response()->json(['message' => 'Empleado eliminado exitosamente.']);
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

        // Solo bloquear si ya existe un EMPLEADO con ese documento.
        // Una persona puede ser cliente y empleado al mismo tiempo (persona compartida).
        $persona = Persona::where('documento_identidad', $numero)->first();
        $exists = $persona && Empleado::where('persona_id', $persona->id)->exists();

        $otherRole = null;
        if ($persona && !$exists) {
            if (Cliente::where('persona_id', $persona->id)->exists()) {
                $otherRole = 'cliente';
            }
        }

        return response()->json(['exists' => $exists, 'other_role' => $otherRole]);
    }

    public function reportePdf()
    {
        // Obtener todos los empleados con sus datos de persona
        $empleados = Empleado::with('persona')->orderBy('codigo_empleado', 'asc')->get();

        // Cargar la vista y generar el PDF (A4 horizontal para más columnas)
        $pdf = \PDF::loadView('admin.empleados.reporte_pdf', compact('empleados'))
            ->setPaper('a4', 'landscape');

        // Descargar el archivo con una marca de tiempo
        return $pdf->download('reporte_empleados_' . now()->format('Ymd_His') . '.pdf');
    }

    public function checkEmail(Request $request)
    {
        $email = $request->input('email');
        if (!$email)
            return response()->json(['exists' => false]);

        $query = Persona::where('email', $email);

        $excludeEmpleadoId = $request->input('exclude_id');
        if ($excludeEmpleadoId) {
            $empleado = Empleado::find($excludeEmpleadoId);
            if ($empleado && $empleado->persona_id) {
                $query->where('id', '!=', $empleado->persona_id);
            }
        }

        return response()->json(['exists' => $query->exists()]);
    }

    public function checkCodigo(Request $request)
    {
        $codigo = $request->input('codigo');
        if (!$codigo)
            return response()->json(['exists' => false]);
        $exists = Empleado::where('codigo_empleado', $codigo)->exists();
        return response()->json(['exists' => $exists]);
    }

    /**
     * Guardar un nuevo departamento on-the-fly (AJAX)
     */
    public function storeDepartamento(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|min:3|max:100',
        ]);

        $nombre = trim($request->nombre);

        // Verificar si ya existe
        $existe = Empleado::whereRaw('LOWER(departamento) = ?', [strtolower($nombre)])->exists();
        if ($existe) {
            return response()->json(['message' => 'Este departamento ya existe.'], 422);
        }

        return response()->json(['departamento' => $nombre]);
    }
}
