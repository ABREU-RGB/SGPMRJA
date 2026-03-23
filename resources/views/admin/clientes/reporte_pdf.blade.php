<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Reporte General de Clientes</title>
    <style>
        /* ── Base ── */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'DejaVu Sans', sans-serif;
            font-size: 10px;
            color: #2d3436;
            padding: 15px 30px;
        }

        /* ── Document Header ── */
        .doc-header {
            width: 100%;
            border-bottom: 3px solid #1e3c72;
            padding-bottom: 6px;
            margin-bottom: 10px;
        }

        .doc-header table {
            width: 100%;
            border: none;
        }

        .doc-header td {
            border: none;
            padding: 0;
            vertical-align: middle;
        }

        .doc-header .logo-cell {
            width: 90px;
        }

        .doc-header .logo-cell img {
            width: 80px;
            height: auto;
        }

        .doc-header .info-cell {
            text-align: center;
        }

        .doc-header .meta-cell {
            width: 130px;
            text-align: right;
        }

        .company-name {
            font-size: 16px;
            font-weight: bold;
            color: #1e3c72;
            letter-spacing: 0.5px;
        }

        .company-detail {
            font-size: 9px;
            color: #3d4852;
            line-height: 1.5;
        }

        .report-title {
            font-size: 13px;
            font-weight: bold;
            color: #1e3c72;
            text-align: center;
            margin-bottom: 4px;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .report-meta {
            font-size: 9px;
            color: #3d4852;
            text-align: right;
            line-height: 1.6;
            font-weight: 600;
        }

        /* ── Summary Bar ── */
        .summary-bar {
            background-color: #f1f3f8;
            border: 1px solid #dfe6f0;
            padding: 6px 14px;
            margin: 0 2px 12px 2px;
        }

        .summary-bar table {
            width: 100%;
            border: none;
        }

        .summary-bar td {
            border: none;
            padding: 0 18px 0 0;
            font-size: 9px;
            color: #2d3436;
        }

        .summary-bar .label {
            color: #3d4852;
            font-weight: 600;
        }

        .summary-bar .value {
            font-weight: bold;
            color: #1e3c72;
        }

        /* ── Data Table ── */
        .data-table {
            width: 100%;
            border-collapse: collapse;
        }

        .data-table thead tr {
            background-color: #1e3c72;
        }

        .data-table thead th {
            color: #ffffff;
            font-weight: 600;
            font-size: 8.5px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            padding: 8px 6px;
            text-align: left;
            border: none;
        }

        .data-table tbody tr {}

        .data-table tbody tr.zebra {
            background-color: #f8f9fc;
        }

        .data-table tbody td {
            padding: 6px;
            font-size: 9px;
            vertical-align: top;
            border: none;
            border-bottom: 1px solid #dde2e8;
            color: #2d3436;
        }

        /* ── Row # column ── */
        .col-num {
            width: 3%;
            text-align: center;
            color: #a0a8b4;
        }

        .col-nombre {
            width: 12%;
            font-weight: 600;
        }

        .col-tipo {
            width: 8%;
        }

        .col-email {
            width: 16%;
        }

        .col-telefono {
            width: 9%;
        }

        .col-documento {
            width: 10%;
        }

        .col-direccion {
            width: 20%;
        }

        .col-ciudad {
            width: 8%;
        }

        .col-estatus {
            width: 6%;
            text-align: center;
        }

        .col-creado {
            width: 8%;
            text-align: center;
        }

        /* ── Status badges ── */
        .badge-activo {
            background-color: #d4edda;
            color: #155724;
            padding: 2px 6px;
            font-size: 8px;
            font-weight: 600;
        }

        .badge-inactivo {
            background-color: #f8d7da;
            color: #721c24;
            padding: 2px 6px;
            font-size: 8px;
            font-weight: 600;
        }

        /* ── Footer ── */
        .doc-footer {
            width: 100%;
            border-top: 1px solid #dfe6f0;
            padding-top: 8px;
            margin-top: 14px;
            font-size: 9px;
            color: #555555;
            font-weight: 600;
        }

        .doc-footer table {
            width: 100%;
            border: none;
        }

        .doc-footer td {
            border: none;
            padding: 0;
        }

        /* ── Page break helper ── */
        @page {
            margin: 35px 45px;
        }
    </style>
</head>

<body>

    {{-- ═══════ DOCUMENT HEADER ═══════ --}}
    <div class="doc-header">
        <table>
            <tr>
                <td class="logo-cell">
                    <img src="{{ public_path('logo.jpg') }}" alt="Logo">
                </td>
                <td class="info-cell">
                    <div class="company-name">Manufacturas R.J. Atlántico C.A.</div>
                    <div class="company-detail">
                        RIF: J-40391423-0 &nbsp;&middot;&nbsp; Telf.: 0414-3558537 / 0255-6640625 &nbsp;&middot;&nbsp;
                        rjatlantico@gmail.com
                    </div>
                    <div class="company-detail">
                        Av. Esquina Calle 35, Locales 1 y 2, Sector Centro — Acarigua, Edo. Portuguesa
                    </div>
                </td>
                <td class="meta-cell">
                    <div class="report-meta">
                        {{ now()->format('d/m/Y') }}<br>
                        {{ now()->format('h:i A') }}
                    </div>
                </td>
            </tr>
        </table>
    </div>

    {{-- ═══════ REPORT TITLE ═══════ --}}
    <div class="report-title">Reporte General de Clientes</div>

    {{-- ═══════ SUMMARY BAR ═══════ --}}
    <div class="summary-bar">
        <table>
            <tr>
                <td>
                    <span class="label">Total Registros:</span>
                    <span class="value">{{ $clientes->count() }}</span>
                </td>
                <td>
                    <span class="label">Activos:</span>
                    <span class="value">{{ $clientes->where('estatus', 1)->count() }}</span>
                </td>
                <td>
                    <span class="label">Inactivos:</span>
                    <span class="value">{{ $clientes->where('estatus', 0)->count() }}</span>
                </td>
                <td>
                    <span class="label">Naturales:</span>
                    <span class="value">{{ $clientes->where('tipo_cliente', 'natural')->count() }}</span>
                </td>
                <td>
                    <span class="label">Jurídicos:</span>
                    <span class="value">{{ $clientes->where('tipo_cliente', 'juridico')->count() }}</span>
                </td>
            </tr>
        </table>
    </div>

    {{-- ═══════ DATA TABLE ═══════ --}}
    <table class="data-table">
        <thead>
            <tr>
                <th class="col-num">#</th>
                <th class="col-nombre">Nombre</th>
                <th class="col-tipo">Tipo</th>
                <th class="col-email">Email</th>
                <th class="col-telefono">Teléfono</th>
                <th class="col-documento">Documento</th>
                <th class="col-direccion">Dirección</th>
                <th class="col-ciudad">Municipio</th>
                <th class="col-estatus">Estatus</th>
                <th class="col-creado">Creado</th>
            </tr>
        </thead>
        <tbody>
            @foreach($clientes as $index => $cliente)
                <tr class="{{ $index % 2 === 1 ? 'zebra' : '' }}">
                    <td class="col-num">{{ $index + 1 }}</td>
                    <td class="col-nombre">{{ $cliente->nombre }}{{ $cliente->apellido ? ' ' . $cliente->apellido : '' }}
                    </td>
                    <td class="col-tipo">{{ ucfirst($cliente->tipo_cliente) }}</td>
                    <td class="col-email">{{ $cliente->email ?? '—' }}</td>
                    <td class="col-telefono">{{ $cliente->telefono ?? '—' }}</td>
                    <td class="col-documento">{{ $cliente->documento ?? '—' }}</td>
                    <td class="col-direccion">{{ $cliente->direccion ?? '—' }}</td>
                    <td class="col-ciudad">{{ $cliente->ciudad ?? '—' }}</td>
                    <td class="col-estatus">
                        <span class="{{ $cliente->estatus == 1 ? 'badge-activo' : 'badge-inactivo' }}">
                            {{ $cliente->estatus == 1 ? 'Activo' : 'Inactivo' }}
                        </span>
                    </td>
                    <td class="col-creado">
                        {{ $cliente->created_at ? \Carbon\Carbon::parse($cliente->created_at)->format('d/m/Y') : '—' }}
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    {{-- ═══════ FOOTER ═══════ --}}
    <div class="doc-footer">
        <table>
            <tr>
                <td>Manufacturas R.J. Atlántico C.A. — Sistema de Gestión de Pedidos</td>
                <td style="text-align: right;">Página 1</td>
            </tr>
        </table>
    </div>

</body>

</html>