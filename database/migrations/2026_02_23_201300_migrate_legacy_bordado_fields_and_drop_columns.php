<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        $tarifasPorNombre = [
            'frontal izquierdo' => 3.00,
            'frontal derecho' => 3.00,
            'manga izquierda' => 3.00,
            'manga derecha' => 3.00,
            'espaldar' => 5.00,
        ];

        $ubicaciones = DB::table('bordado_ubicaciones')->pluck('id', 'nombre');

        DB::table('detalle_cotizacion')
            ->select('id', 'lleva_bordado', 'ubicacion_logo', 'cantidad_logo')
            ->orderBy('id')
            ->chunk(300, function ($rows) use ($ubicaciones, $tarifasPorNombre) {
                foreach ($rows as $row) {
                    if (!$row->lleva_bordado || empty($row->ubicacion_logo)) {
                        continue;
                    }

                    $nombre = trim((string) $row->ubicacion_logo);
                    $nombreLower = mb_strtolower($nombre);
                    $cantidad = (int) ($row->cantidad_logo ?: 1);

                    DB::table('detalle_cotizacion_bordado')->insert([
                        'detalle_cotizacion_id' => $row->id,
                        'ubicacion_bordado_id' => $ubicaciones[$nombre] ?? null,
                        'nombre_aplicado' => $nombre,
                        'es_personalizada' => !isset($ubicaciones[$nombre]),
                        'cantidad' => max(1, $cantidad),
                        'precio_aplicado' => $tarifasPorNombre[$nombreLower] ?? 0,
                        'orden' => 0,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                }
            });

        DB::table('detalle_pedido')
            ->select('id', 'lleva_bordado', 'ubicacion_logo', 'cantidad_logo')
            ->orderBy('id')
            ->chunk(300, function ($rows) use ($ubicaciones, $tarifasPorNombre) {
                foreach ($rows as $row) {
                    if (!$row->lleva_bordado || empty($row->ubicacion_logo)) {
                        continue;
                    }

                    $nombre = trim((string) $row->ubicacion_logo);
                    $nombreLower = mb_strtolower($nombre);
                    $cantidad = (int) ($row->cantidad_logo ?: 1);

                    DB::table('detalle_pedido_bordado')->insert([
                        'detalle_pedido_id' => $row->id,
                        'ubicacion_bordado_id' => $ubicaciones[$nombre] ?? null,
                        'nombre_aplicado' => $nombre,
                        'es_personalizada' => !isset($ubicaciones[$nombre]),
                        'cantidad' => max(1, $cantidad),
                        'precio_aplicado' => $tarifasPorNombre[$nombreLower] ?? 0,
                        'orden' => 0,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                }
            });

        Schema::table('detalle_cotizacion', function (Blueprint $table) {
            $table->dropColumn(['ubicacion_logo', 'cantidad_logo']);
        });

        Schema::table('detalle_pedido', function (Blueprint $table) {
            $table->dropColumn(['ubicacion_logo', 'cantidad_logo']);
        });
    }

    public function down(): void
    {
        Schema::table('detalle_cotizacion', function (Blueprint $table) {
            $table->string('ubicacion_logo')->nullable()->after('nombre_logo');
            $table->integer('cantidad_logo')->nullable()->after('ubicacion_logo');
        });

        Schema::table('detalle_pedido', function (Blueprint $table) {
            $table->string('ubicacion_logo')->nullable()->after('talla');
            $table->integer('cantidad_logo')->nullable()->after('ubicacion_logo');
        });
    }
};
