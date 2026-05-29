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
        // Normalizar valores libres ingresados antes de que el campo fuera un select
        $map = [
            'Kilos'       => 'Kg',
            'kilos'       => 'Kg',
            'kg'          => 'Kg',
            'kilogramo'   => 'Kg',
            'Kilogramo'   => 'Kg',
            'metro'       => 'Metro',
            'metros'      => 'Metro',
            'Metros'      => 'Metro',
            'unidad'      => 'Unidad',
            'unidades'    => 'Unidad',
            'Unidades'    => 'Unidad',
            'gramo'       => 'Gramo',
            'Gramos'      => 'Gramo',
            'gramos'      => 'Gramo',
            'rollo'       => 'Rollo',
            'rollos'      => 'Rollo',
            'Rollos'      => 'Rollo',
            'cono'        => 'Cono',
            'conos'       => 'Cono',
            'Conos'       => 'Cono',
            'docena'      => 'Docena',
            'docenas'     => 'Docena',
            'Docenas'     => 'Docena',
        ];

        foreach ($map as $old => $new) {
            DB::table('insumo')
                ->where('unidad_medida', $old)
                ->update(['unidad_medida' => $new]);
        }
    }

    public function down(): void
    {
        // No reversible: los valores originales libres no se pueden recuperar
    }
};
