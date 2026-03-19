<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('orden_produccion', function (Blueprint $table) {
            $table->date('fecha_fin_real')->nullable()->after('fecha_fin_estimada');
        });
    }

    public function down(): void
    {
        Schema::table('orden_produccion', function (Blueprint $table) {
            $table->dropColumn('fecha_fin_real');
        });
    }
};
