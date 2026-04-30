<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use App\Models\Empleado;
use App\Models\Pedido;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index(Request $request)
    {
        // Valores por defecto seguros — el dashboard siempre renderiza
        $totalClientes = 0;
        $totalProductos = 0;
        $totalEmpleados = 0;
        $totalProveedores = 0;
        $pedidosLabels = ['Pendiente', 'En Proceso', 'Completado', 'Cancelado'];
        $pedidosValues = [0, 0, 0, 0];
        $totalPedidos = 0;
        $empleadosLabels = [];
        $empleadosValues = [];
        $totalEmpleadosChart = 0;

        try {
            // 1 query: conteos de maestros usando subqueries
            $counts = DB::selectOne("
                SELECT
                    (SELECT COUNT(*) FROM cliente WHERE deleted_at IS NULL) as clientes,
                    (SELECT COUNT(*) FROM producto WHERE deleted_at IS NULL) as productos,
                    (SELECT COUNT(*) FROM empleado WHERE deleted_at IS NULL) as empleados,
                    (SELECT COUNT(*) FROM proveedor WHERE deleted_at IS NULL) as proveedores
            ");

            $totalClientes = $counts->clientes ?? 0;
            $totalProductos = $counts->productos ?? 0;
            $totalEmpleados = $counts->empleados ?? 0;
            $totalProveedores = $counts->proveedores ?? 0;
        } catch (\Exception $e) {
            \Log::warning('Dashboard: error al consultar conteos de maestros — ' . $e->getMessage());
        }

        try {
            // 1 query: estados de pedidos con SUM(CASE WHEN)
            $pedidoStats = DB::selectOne("
                SELECT
                    SUM(CASE WHEN estado = 'Pendiente' THEN 1 ELSE 0 END) as pendiente,
                    SUM(CASE WHEN estado = 'En Proceso' THEN 1 ELSE 0 END) as en_proceso,
                    SUM(CASE WHEN estado = 'Completado' THEN 1 ELSE 0 END) as completado,
                    SUM(CASE WHEN estado = 'Cancelado' THEN 1 ELSE 0 END) as cancelado
                FROM pedido WHERE deleted_at IS NULL
            ");

            $pedidosValues = [
                (int) ($pedidoStats->pendiente ?? 0),
                (int) ($pedidoStats->en_proceso ?? 0),
                (int) ($pedidoStats->completado ?? 0),
                (int) ($pedidoStats->cancelado ?? 0),
            ];
            $totalPedidos = array_sum($pedidosValues);
        } catch (\Exception $e) {
            \Log::warning('Dashboard: error al consultar pedidos — ' . $e->getMessage());
        }

        try {
            // 1 query: personal por departamento (JOIN con tabla departamento)
            $personalPorDepto = Empleado::join('departamento', 'empleado.departamento_id', '=', 'departamento.id')
                ->whereNull('empleado.deleted_at')
                ->whereNotNull('empleado.departamento_id')
                ->selectRaw('departamento.nombre as departamento, COUNT(*) as total')
                ->groupBy('departamento.nombre')
                ->orderBy('total', 'desc')
                ->get();

            $empleadosLabels = $personalPorDepto->pluck('departamento')->toArray();
            $empleadosValues = $personalPorDepto->pluck('total')->toArray();
            $totalEmpleadosChart = array_sum($empleadosValues);
        } catch (\Exception $e) {
            \Log::warning('Dashboard: error al consultar empleados por departamento — ' . $e->getMessage());
        }

        return view('dashboard', compact(
            'totalClientes',
            'totalProductos',
            'totalEmpleados',
            'totalProveedores',
            'pedidosLabels',
            'pedidosValues',
            'totalPedidos',
            'empleadosLabels',
            'empleadosValues',
            'totalEmpleadosChart'
        ));
    }


    /*Language Translation*/
    public function lang($locale)
    {
        if ($locale) {
            App::setLocale($locale);
            Session::put('lang', $locale);
            Session::save();
            return redirect()->back()->with('locale', $locale);
        } else {
            return redirect()->back();
        }
    }


}

