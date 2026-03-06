@extends('admin.layouts.app')

@section('title', 'Reporte de Eficiencia')
@push('styles')
    <link href="{{ URL::asset('assets/libs/sweetalert2/sweetalert2.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap5.min.css" rel="stylesheet" type="text/css" />
    <link href="https://cdn.datatables.net/responsive/2.2.9/css/responsive.bootstrap5.min.css" rel="stylesheet" type="text/css" />
    <style>
        .card-body { overflow-x: auto; }
        .dataTables_filter { display: none; }

        #eficienciaTable {
            width: 100% !important;
            table-layout: fixed;
            font-size: 13px;
        }
        #eficienciaTable th,
        #eficienciaTable td {
            padding: 0.4rem 0.6rem;
            vertical-align: middle;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }
        /* Anchos de columna (suman 100%) */
        #eficienciaTable th:nth-child(1) { width: 35%; }                      /* Producto            */
        #eficienciaTable th:nth-child(2) { width: 16%; text-align: center; } /* Cant. Solicitada    */
        #eficienciaTable th:nth-child(3) { width: 16%; text-align: center; } /* Total Producido     */
        #eficienciaTable th:nth-child(4) { width: 16%; text-align: center; } /* Total Defectuoso    */
        #eficienciaTable th:nth-child(5) { width: 17%; text-align: center; } /* Eficiencia          */

        /* Eficiencia: overflow visible para la barra de progreso */
        #eficienciaTable td:nth-child(2),
        #eficienciaTable td:nth-child(3),
        #eficienciaTable td:nth-child(4),
        #eficienciaTable td:nth-child(5) {
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
            <h4 class="mb-sm-0">Reporte de Eficiencia</h4>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-xl-12">
        <div class="card card-reportes">
            <div class="card-header">
                <h4 class="card-title mb-0">Eficiencia por Orden de Producción</h4>
            </div>
            <div class="card-body">
                <div id="eficienciaPorOrdenChart" class="apex-charts" dir="ltr" style="height: 350px;"></div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-xl-12">
        <div class="card card-reportes">
            <div class="card-header">
                <h4 class="card-title mb-0">Detalle de Eficiencia por Orden</h4>
            </div>
            <div class="card-body">
                    <table class="table table-bordered table-striped table-sm align-middle dt-reportes" id="eficienciaTable">
                        <thead>
                            <tr>
                                <th>Producto</th>
                                <th>Cantidad Solicitada</th>
                                <th>Total Producido</th>
                                <th>Total Defectuoso</th>
                                <th>Eficiencia</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($eficienciaPorOrden as $item)
                            <tr>
                                <td>{{ $item['producto'] }}</td>
                                <td>{{ $item['cantidad_solicitada'] }}</td>
                                <td>{{ $item['total_producido'] }}</td>
                                <td>{{ $item['total_defectuoso'] }}</td>
                                <td>
                                    <div class="progress" style="height: 5px;">
                                        <div class="progress-bar {{ $item['eficiencia'] >= 90 ? 'bg-success' : ($item['eficiencia'] >= 70 ? 'bg-warning' : 'bg-danger') }}" 
                                            role="progressbar" 
                                            style="width: {{ $item['eficiencia'] }}%;" 
                                            aria-valuenow="{{ $item['eficiencia'] }}" 
                                            aria-valuemin="0" 
                                            aria-valuemax="100">
                                        </div>
                                    </div>
                                    <span>{{ $item['eficiencia'] }}%</span>
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
        $('#eficienciaTable').DataTable({
            autoWidth: false,
            language: lenguajeData,
            order: [[4, 'desc']]
        });

        var ordenes = [];
        var eficiencias = [];

        @foreach($eficienciaPorOrden as $item)
            ordenes.push('Orden #{{ $item['orden_id'] }}');
            eficiencias.push({{ $item['eficiencia'] }});
        @endforeach

        var eficienciaPorOrdenOptions = {
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
                    horizontal: true,
                    distributed: true,
                    dataLabels: {
                        position: 'top'
                    },
                }
            },
            colors: eficiencias.map(function(value) {
                if (value >= 90) return '#0ab39c';
                else if (value >= 70) return '#f7b84b';
                else return '#f06548';
            }),
            dataLabels: {
                enabled: true,
                formatter: function (val) {
                    return val + "%";
                },
                offsetX: 20,
                style: {
                    fontSize: '12px',
                    colors: ['#000']
                }
            },
            xaxis: {
                categories: ordenes,
                labels: {
                    formatter: function (val) {
                        return val + "%";
                    }
                }
            },
            yaxis: {
                title: {
                    text: 'Órdenes de Producción'
                }
            },
            tooltip: {
                y: {
                    formatter: function (val) {
                        return val + "%";
                    }
                }
            }
        };

        var eficienciaPorOrdenChart = new ApexCharts(document.querySelector("#eficienciaPorOrdenChart"), eficienciaPorOrdenOptions);
        eficienciaPorOrdenChart.render();
    });
</script>
@endpush
