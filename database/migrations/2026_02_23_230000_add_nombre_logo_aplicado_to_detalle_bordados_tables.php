<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('detalle_cotizacion_bordado', function (Blueprint $table) {
            $table->string('nombre_logo_aplicado', 120)->nullable()->after('nombre_aplicado');
        });

        Schema::table('detalle_pedido_bordado', function (Blueprint $table) {
            $table->string('nombre_logo_aplicado', 120)->nullable()->after('nombre_aplicado');
        });

        DB::statement('UPDATE detalle_cotizacion_bordado dcb INNER JOIN detalle_cotizacion dc ON dc.id = dcb.detalle_cotizacion_id SET dcb.nombre_logo_aplicado = dc.nombre_logo WHERE dcb.nombre_logo_aplicado IS NULL');
        DB::statement('UPDATE detalle_pedido_bordado dpb INNER JOIN detalle_pedido dp ON dp.id = dpb.detalle_pedido_id SET dpb.nombre_logo_aplicado = dp.nombre_logo WHERE dpb.nombre_logo_aplicado IS NULL');
    }

    public function down(): void
    {
        Schema::table('detalle_cotizacion_bordado', function (Blueprint $table) {
            $table->dropColumn('nombre_logo_aplicado');
        });

        Schema::table('detalle_pedido_bordado', function (Blueprint $table) {
            $table->dropColumn('nombre_logo_aplicado');
        });
    }
};
