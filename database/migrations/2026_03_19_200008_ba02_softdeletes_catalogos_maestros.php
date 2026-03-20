<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * BA-02: Agregar SoftDeletes a catálogos maestros.
 *
 * Tablas: banco, color, talla, logo, bordado_ubicacion, tipo_producto
 *
 * Protege contra DELETE directo accidental. Los catálogos que tienen campo
 * 'activo' (color, talla, bordado_ubicacion) ya usaban ese flag como soft-delete
 * de aplicación; ahora tienen protección real a nivel DB.
 */
return new class extends Migration
{
    private array $tables = [
        'banco',
        'color',
        'talla',
        'logo',
        'bordado_ubicacion',
        'tipo_producto',
    ];

    public function up(): void
    {
        foreach ($this->tables as $table) {
            Schema::table($table, function (Blueprint $t) {
                $t->softDeletes();
            });
        }
    }

    public function down(): void
    {
        foreach ($this->tables as $table) {
            Schema::table($table, function (Blueprint $t) {
                $t->dropSoftDeletes();
            });
        }
    }
};
