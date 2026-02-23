<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('tallas', function (Blueprint $table) {
            $table->id();
            $table->string('nombre')->unique()->comment('Valor interno de talla (Ej: XS, M, Talla Unica)');
            $table->string('etiqueta')->nullable()->comment('Etiqueta visual para UI (Ej: Única)');
            $table->string('grupo')->nullable()->comment('Agrupación visual: Única, Numéricas, Letras');
            $table->unsignedInteger('orden')->default(0)->comment('Orden de despliegue en UI');
            $table->boolean('activo')->default(true)->comment('Permite desactivar tallas sin borrarlas');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tallas');
    }
};
