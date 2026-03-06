<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('produccion_diaria', function (Blueprint $table) {
            $table->dropForeign('produccion_diaria_operario_id_foreign');
            $table->renameColumn('operario_id', 'empleado_id');
            $table->foreign('empleado_id')->references('id')->on('empleado')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('produccion_diaria', function (Blueprint $table) {
            $table->dropForeign('produccion_diaria_empleado_id_foreign');
            $table->renameColumn('empleado_id', 'operario_id');
            $table->foreign('operario_id')->references('id')->on('user')->onDelete('cascade');
        });
    }
};
