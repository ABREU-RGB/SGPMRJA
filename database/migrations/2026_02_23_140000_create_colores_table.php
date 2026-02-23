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
        Schema::create('colores', function (Blueprint $table) {
            $table->id();
            $table->string('nombre')->unique()->comment('Nombre comercial del color (Ej: Azul Marino)');
            $table->string('hex_referencial', 7)->comment('Color HEX referencial para el swatch (#1B3A5C)');
            $table->string('grupo')->nullable()->comment('Agrupación visual: Básicos, Pasteles, Oscuros, etc.');
            $table->boolean('activo')->default(true)->comment('Permite desactivar colores sin borrarlos');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('colores');
    }
};
