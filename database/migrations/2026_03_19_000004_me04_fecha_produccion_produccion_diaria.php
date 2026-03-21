<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('produccion_diaria', function (Blueprint $table) {
            $table->date('fecha_produccion')->nullable()->after('orden_id');
        });

        // Poblar registros existentes con la fecha de created_at
        DB::statement("UPDATE produccion_diaria SET fecha_produccion = DATE(created_at) WHERE fecha_produccion IS NULL");
    }

    public function down(): void
    {
        Schema::table('produccion_diaria', function (Blueprint $table) {
            $table->dropColumn('fecha_produccion');
        });
    }
};
