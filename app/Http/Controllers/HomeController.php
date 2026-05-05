<?php

namespace App\Http\Controllers;

use App\Models\RecoveryAttempt;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

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

        $pedidosLabels = ['Pendiente', 'En Proceso', 'Completado', 'Cancelado'];
        $pedidosValues = [
            (int) ($pedidoStats->pendiente ?? 0),
            (int) ($pedidoStats->en_proceso ?? 0),
            (int) ($pedidoStats->completado ?? 0),
            (int) ($pedidoStats->cancelado ?? 0),
        ];
        $totalPedidos = array_sum($pedidosValues);

        // 1 query: personal por departamento (join con tabla departamento normalizada)
        $personalPorDepto = DB::select("
            SELECT d.nombre as departamento, COUNT(e.id) as total
            FROM empleado e
            JOIN departamento d ON e.departamento_id = d.id
            WHERE e.deleted_at IS NULL AND d.deleted_at IS NULL
            GROUP BY d.id, d.nombre
            ORDER BY total DESC
        ");

        $empleadosLabels = array_column($personalPorDepto, 'departamento');
        $empleadosValues = array_map('intval', array_column($personalPorDepto, 'total'));
        $totalEmpleadosChart = array_sum($empleadosValues);

        } catch (\Exception $e) {
            \Log::warning('Dashboard: error al consultar datos de pedidos/empleados — ' . $e->getMessage());
        }

        // Notificación: intento de recuperación reciente para el usuario actual
        $recoveryAlert = $this->getRecoveryAlert();

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
            'totalEmpleadosChart',
            'recoveryAlert'
        ));
    }

    /**
     * Devuelve el último intento de recuperación posterior al login del usuario,
     * para mostrarlo como banner informativo. Solo se muestra una vez por sesión.
     */
    private function getRecoveryAlert(): ?array
    {
        $user = Auth::user();
        if (!$user) return null;

        if (Session::get('recovery_alert_shown')) {
            return null;
        }

        $attempt = RecoveryAttempt::where('user_id', $user->id)
            ->where('created_at', '>=', now()->subHours(24))
            ->orderByDesc('created_at')
            ->first();

        if (!$attempt) return null;

        Session::put('recovery_alert_shown', true);

        return [
            'fecha'     => $attempt->created_at->format('d/m/Y H:i'),
            'ip'        => $attempt->ip,
            'resultado' => $attempt->resultado,
            'tipo'      => $attempt->tipo,
        ];
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

