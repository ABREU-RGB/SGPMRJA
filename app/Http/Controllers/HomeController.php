<?php

namespace App\Http\Controllers;
 
use Illuminate\Http\Request; 
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;
use App\Models\Cliente;
use App\Models\Producto;
use App\Models\Empleado;
use App\Models\Proveedor;
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
        // Widgets: conteos de Maestros
        $totalClientes = Cliente::count();
        $totalProductos = Producto::count();
        $totalEmpleados = Empleado::count();
        $totalProveedores = Proveedor::count();

        // Gráfico 1: Estado de Pedidos (Preparado para Chart)
        $pedidosLabels = ['Pendiente', 'En Proceso', 'Completado', 'Cancelado'];
        $pedidosValues = [
            Pedido::where('estado', 'Pendiente')->count(),
            Pedido::where('estado', 'En Proceso')->count(),
            Pedido::where('estado', 'Completado')->count(),
            Pedido::where('estado', 'Cancelado')->count(),
        ];
        $totalPedidos = array_sum($pedidosValues);

        // Gráfico 2: Personal por Departamento
        $personalPorDepto = Empleado::whereNotNull('departamento')
            ->selectRaw('departamento, COUNT(*) as total')
            ->groupBy('departamento')
            ->orderBy('total', 'desc')
            ->get();
        
        $empleadosLabels = $personalPorDepto->pluck('departamento')->toArray();
        $empleadosValues = $personalPorDepto->pluck('total')->toArray();
        $totalEmpleadosChart = array_sum($empleadosValues);

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
