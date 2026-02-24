<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('detalle_pedido_bordado', function (Blueprint $table) {
            $table->id();
            $table->foreignId('detalle_pedido_id')->constrained('detalle_pedido')->cascadeOnDelete();
            $table->foreignId('ubicacion_bordado_id')->nullable()->constrained('bordado_ubicaciones')->nullOnDelete();
            $table->string('nombre_aplicado');
            $table->boolean('es_personalizada')->default(false);
            $table->unsignedInteger('cantidad')->default(1);
            $table->decimal('precio_aplicado', 10, 2)->default(0);
            $table->unsignedInteger('orden')->default(0);
            $table->timestamps();

            $table->index('detalle_pedido_id', 'idx_det_ped_bordado_detalle');
            $table->index('ubicacion_bordado_id', 'idx_det_ped_bordado_ubicacion');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('detalle_pedido_bordado');
    }
};
