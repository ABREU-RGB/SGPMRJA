@extends('admin.layouts.app')

@section('title', 'Reporte de Rendimiento de Empleados')
@push('styles')
    <link href="{{ URL::asset('assets/libs/sweetalert2/sweetalert2.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap5.min.css" rel="stylesheet" type="text/css" />
    <link href="https://cdn.datatables.net/responsive/2.2.9/css/responsive.bootstrap5.min.css" rel="stylesheet"
        type="text/css" />
    <style>
        .card-body { overflow-x: auto; }
        .dataTables_filter { display: none; }

        #operariosTable {
            width: 100% !important;
            table-layout: fixed;
            font-size: 13px;
        }
        #operariosTable th,
        #operariosTable td {
            padding: 0.4rem 0.6rem;
            vertical-align: middle;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }
        /* Anchos de columna (suman 100%) */
        #operariosTable th:nth-child(1) { width: 28%; }                      /* Nombre           */
        #operariosTable th:nth-child(2) { width: 12%; text-align: center; } /* Total Órdenes    */
        #operariosTable th:nth-child(3) { width: 14%; text-align: center; } /* Total Producido  */
        #operariosTable th:nth-child(4) { width: 14%; text-align: center; } /* Total Defectuoso */
        #operariosTable th:nth-child(5) { width: 18%; text-align: center; } /* Eficiencia       */
        #operariosTable th:nth-child(6) { width: 14%; text-align: center; } /* Promedio p/Orden */

        #operariosTable td:nth-child(2),
        #operariosTable td:nth-child(3),
        #operariosTable td:nth-child(4),
        #operariosTable td:nth-child(5),
        #operariosTable td:nth-child(6) {
            overflow: visible;
            text-overflow: clip;
            text-align: center;
        }
    </style>
@endpush
@section('content')
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                <h4 class="mb-sm-0">Reporte de Rendimiento de Empleados</h4>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-xl-6">
            <div class="card card-reportes">
                <div class="card-header">
                    <h4 class="card-title mb-0">Producción por Empleado</h4>
                </div>
                <div class="card-body">
                    <div id="produccionPorOperarioChart" class="apex-charts" dir="ltr"></div>
                </div>
            </div>
        </div>
        <div class="col-xl-6">
            <div class="card card-reportes">
                <div class="card-header">
                    <h4 class="card-title mb-0">Eficiencia por Empleado</h4>
                </div>
                <div class="card-body">
                    <div id="eficienciaPorOperarioChart" class="apex-charts" dir="ltr"></div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-xl-12">
            <div class="card card-reportes">
                <div class="card-header">
                    <h4 class="card-title mb-0">Detalle de Rendimiento por Empleado</h4>
                </div>
                <div class="card-body">
                        <table class="table table-bordered table-striped table-sm align-middle dt-reportes" id="operariosTable">
                            <thead>
                                <tr>
                                    <th>Nombre</th>
                                    <th>Total Órdenes</th>
                                    <th>Total Producido</th>
                                    <th>Total Defectuoso</th>
                                    <th>Eficiencia</th>
                                    <th>Promedio por Orden</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($rendimientoOperarios as $operario)
                                    <tr>
                                        <td>{{ $operario['nombre'] }}</td>
                                        <td>{{ $operario['total_ordenes'] }}</td>
                                        <td>{{ $operario['total_producido'] }}</td>
                                        <td>{{ $operario['total_defectuoso'] }}</td>
                                        <td>
                                            <div class="progress" style="height: 5px;">
                                                <div class="progress-bar {{ $operario['eficiencia'] >= 90 ? 'bg-success' : ($operario['eficiencia'] >= 70 ? 'bg-warning' : 'bg-danger') }}"
                                                    role="progressbar" style="width: {{ $operario['eficiencia'] }}%;"
                                                    aria-valuenow="{{ $operario['eficiencia'] }}" aria-valuemin="0"
                                                    aria-valuemax="100">
                                                </div>
                                            </div>
                                            <span>{{ $operario['eficiencia'] }}%</span>
                                        </td>
                                        <td>{{ $operario['total_ordenes'] > 0 ? round($operario['total_producido'] / $operario['total_ordenes']) : 0 }}
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap5.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.2.9/js/dataTables.responsive.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.2.9/js/responsive.bootstrap5.min.js"></script>
    <script src="{{ URL::asset('assets/libs/sweetalert2/sweetalert2.min.js') }}"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            $('#operariosTable').DataTable({
                autoWidth: false,
                language: lenguajeData,
                order: [[4, 'desc']]
            });

            var operarios = [];
            var produccion = [];
            var eficiencias = [];

            @foreach($rendimientoOperarios as $operario)
                operarios.push('{{ $operario['nombre'] }}');
                produccion.push({{ $operario['total_producido'] }});
                eficiencias.push({{ $operario['eficiencia'] }});
            @endforeach

            var produccionPorOperarioOptions = {
                series: [{
                    name: 'Producción Total',
                    data: produccion
                }],
                chart: {
                    type: 'bar',
                    height: 350
                },
                plotOptions: {
                    bar: {
                        borderRadius: 4,
                        horizontal: false,
                    }
                },
                dataLabels: {
                    enabled: false
                },
                xaxis: {
                    categories: operarios,
                },
                colors: ['#0ab39c'],
                tooltip: {
                    y: {
                        formatter: function (val) {
                            return val + " unidades";
                        }
                    }
                }
            };

            var produccionPorOperarioChart = new ApexCharts(document.querySelector("#produccionPorOperarioChart"), produccionPorOperarioOptions);
            produccionPorOperarioChart.render();

            var eficienciaPorOperarioOptions = {
                series: [{
                    name: 'Eficiencia',
                    data: eficiencias
                }],
                chart: {
                    type: 'bar',
                    height: 350
                },
                plotOptions: {
                    bar: {
                        borderRadius: 4,
                        horizontal: false,
                        distributed: true,
                        dataLabels: {
                            position: 'top'
                        },
                    }
                },
                colors: eficiencias.map(function (value) {
                    if (value >= 90) return '#0ab39c';
                    else if (value >= 70) return '#f7b84b';
                    else return '#f06548';
                }),
                dataLabels: {
                    enabled: true,
                    formatter: function (val) {
                        return val + "%";
                    },
                    offsetY: -20,
                    style: {
                        fontSize: '12px',
                        colors: ['#000']
                    }
                },
                xaxis: {
                    categories: operarios,
                },
                yaxis: {
                    title: {
                        text: 'Eficiencia (%)'
                    },
                    max: 100
                },
                tooltip: {
                    y: {
                        formatter: function (val) {
                            return val + "%";
                        }
                    }
                }
            };

            var eficienciaPorOperarioChart = new ApexCharts(document.querySelector("#eficienciaPorOperarioChart"), eficienciaPorOperarioOptions);
            eficienciaPorOperarioChart.render();
        });
    </script>
@endpush