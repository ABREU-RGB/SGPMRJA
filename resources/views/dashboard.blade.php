@extends('admin.layouts.app')

@section('title', 'Dashboard')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                <h4 class="mb-sm-0">Dashboard</h4>

                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item active">Dashboard</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <!-- Widgets de Maestros -->
    <div class="row">
        <!-- Total Clientes -->
        <div class="col-xl-3 col-md-6">
            <div class="card card-animate">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-grow-1">
                            <p class="text-uppercase fw-medium text-muted mb-0">Total Clientes</p>
                            <h4 class="fs-22 fw-semibold mb-0">{{ $totalClientes }}</h4>
                        </div>
                        <div class="avatar-sm flex-shrink-0">
                            <span class="avatar-title bg-soft-primary rounded fs-3">
                                <i class="ri-user-star-line text-primary"></i>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Total Productos -->
        <div class="col-xl-3 col-md-6">
            <div class="card card-animate">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-grow-1">
                            <p class="text-uppercase fw-medium text-muted mb-0">Total Productos</p>
                            <h4 class="fs-22 fw-semibold mb-0">{{ $totalProductos }}</h4>
                        </div>
                        <div class="avatar-sm flex-shrink-0">
                            <span class="avatar-title bg-soft-success rounded fs-3">
                                <i class="ri-t-shirt-line text-success"></i>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Total Empleados -->
        <div class="col-xl-3 col-md-6">
            <div class="card card-animate">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-grow-1">
                            <p class="text-uppercase fw-medium text-muted mb-0">Total Empleados</p>
                            <h4 class="fs-22 fw-semibold mb-0">{{ $totalEmpleados }}</h4>
                        </div>
                        <div class="avatar-sm flex-shrink-0">
                            <span class="avatar-title bg-soft-info rounded fs-3">
                                <i class="ri-user-settings-line text-info"></i>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Total Proveedores -->
        <div class="col-xl-3 col-md-6">
            <div class="card card-animate">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-grow-1">
                            <p class="text-uppercase fw-medium text-muted mb-0">Total Proveedores</p>
                            <h4 class="fs-22 fw-semibold mb-0">{{ $totalProveedores }}</h4>
                        </div>
                        <div class="avatar-sm flex-shrink-0">
                            <span class="avatar-title bg-soft-warning rounded fs-3">
                                <i class="ri-truck-line text-warning"></i>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Gráficos ApexCharts -->
    <div class="row">
        <!-- Estado de Pedidos -->
        <div class="col-xl-6">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title mb-0">Estado de Pedidos</h4>
                </div>
                <div class="card-body">
                    <div id="estadoPedidosChart"></div>
                </div>
            </div>
        </div>
        <!-- Personal por Departamento -->
        <div class="col-xl-6">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title mb-0">Personal por Departamento</h4>
                </div>
                <div class="card-body">
                    <div id="personalDeptoChart"></div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // ==========================================
            // DATOS DEL BACKEND
            // ==========================================
            const pedidosLabels = @json($pedidosLabels);
            const pedidosValues = @json($pedidosValues);
            const totalPedidos = {{ $totalPedidos }};

            const empleadosLabels = @json($empleadosLabels);
            const empleadosValues = @json($empleadosValues);
            const totalEmpleados = {{ $totalEmpleadosChart }};

            // ==========================================
            // GRÁFICO 1: ESTADO DE PEDIDOS (DONUT)
            // ==========================================
            const pedidosContainer = document.querySelector("#estadoPedidosChart");
            
            if (totalPedidos > 0 && pedidosContainer) {
                var pedidosOptions = {
                    series: pedidosValues,
                    chart: {
                        type: 'donut',
                        height: 350
                    },
                    labels: pedidosLabels,
                    colors: ['#3577f1', '#f7b84b', '#0ab39c', '#f06548'],
                    legend: {
                        position: 'bottom'
                    },
                    plotOptions: {
                        pie: {
                            donut: {
                                size: '65%',
                                labels: {
                                    show: true,
                                    total: {
                                        show: true,
                                        label: 'Total Pedidos',
                                        fontSize: '14px',
                                        fontWeight: 600,
                                        color: '#878a99'
                                    }
                                }
                            }
                        }
                    },
                    dataLabels: {
                        enabled: true,
                        formatter: function(val, opts) {
                            return opts.w.config.series[opts.seriesIndex];
                        }
                    },
                    responsive: [{
                        breakpoint: 480,
                        options: {
                            chart: { height: 280 },
                            legend: { position: 'bottom' }
                        }
                    }]
                };
                new ApexCharts(pedidosContainer, pedidosOptions).render();
            } else if (pedidosContainer) {
                // Mensaje Elegante: No hay datos
                pedidosContainer.innerHTML = `
                    <div class="text-center py-5">
                        <div class="avatar-md mx-auto mb-3">
                            <div class="avatar-title bg-soft-light rounded-circle text-muted fs-1">
                                <i class="ri-folder-info-line"></i>
                            </div>
                        </div>
                        <h5 class="text-muted">No hay datos suficientes</h5>
                        <p class="text-muted mb-0">Registre nuevos pedidos para ver estadísticas.</p>
                    </div>
                `;
            }

            // ==========================================
            // GRÁFICO 2: PERSONAL POR DEPARTAMENTO (BAR)
            // ==========================================
            const personalContainer = document.querySelector("#personalDeptoChart");

            if (totalEmpleados > 0 && personalContainer) {
                var deptoOptions = {
                    series: [{
                        name: 'Empleados',
                        data: empleadosValues
                    }],
                    chart: {
                        type: 'bar',
                        height: 350,
                        toolbar: { show: false }
                    },
                    plotOptions: {
                        bar: {
                            borderRadius: 4,
                            horizontal: true,
                            barHeight: '50%',
                            distributed: true
                        }
                    },
                    colors: ['#3577f1', '#0ab39c', '#f7b84b', '#f06548', '#299cdb', '#405189', '#66d1d1'],
                    dataLabels: {
                        enabled: true,
                        style: {
                            fontSize: '13px',
                            fontWeight: 600
                        }
                    },
                    xaxis: {
                        categories: empleadosLabels,
                        labels: {
                            style: { fontSize: '12px' }
                        }
                    },
                    yaxis: {
                        labels: {
                            style: { fontSize: '13px' }
                        }
                    },
                    legend: { show: false },
                    tooltip: {
                        y: {
                            formatter: function(val) {
                                return val + ' empleado(s)';
                            }
                        }
                    }
                };
                new ApexCharts(personalContainer, deptoOptions).render();
            } else if (personalContainer) {
                // Mensaje Elegante: No hay datos
                personalContainer.innerHTML = `
                    <div class="text-center py-5">
                        <div class="avatar-md mx-auto mb-3">
                            <div class="avatar-title bg-soft-light rounded-circle text-muted fs-1">
                                <i class="ri-user-unfollow-line"></i>
                            </div>
                        </div>
                        <h5 class="text-muted">No hay datos suficientes</h5>
                        <p class="text-muted mb-0">Asigne departamentos a los empleados para ver estadísticas.</p>
                    </div>
                `;
            }
        });
    </script>
@endpush
