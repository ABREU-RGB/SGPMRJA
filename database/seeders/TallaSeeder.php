<?php

namespace Database\Seeders;

use App\Models\Talla;
use Illuminate\Database\Seeder;

class TallaSeeder extends Seeder
{
    public function run(): void
    {
        $tallas = [
            ['nombre' => 'Talla Unica', 'etiqueta' => 'Única', 'grupo' => 'Única', 'orden' => 10],
            ['nombre' => '2', 'etiqueta' => '2', 'grupo' => 'Numéricas', 'orden' => 20],
            ['nombre' => '4', 'etiqueta' => '4', 'grupo' => 'Numéricas', 'orden' => 21],
            ['nombre' => '6', 'etiqueta' => '6', 'grupo' => 'Numéricas', 'orden' => 22],
            ['nombre' => '8', 'etiqueta' => '8', 'grupo' => 'Numéricas', 'orden' => 23],
            ['nombre' => '10', 'etiqueta' => '10', 'grupo' => 'Numéricas', 'orden' => 24],
            ['nombre' => '12', 'etiqueta' => '12', 'grupo' => 'Numéricas', 'orden' => 25],
            ['nombre' => '14', 'etiqueta' => '14', 'grupo' => 'Numéricas', 'orden' => 26],
            ['nombre' => '16', 'etiqueta' => '16', 'grupo' => 'Numéricas', 'orden' => 27],
            ['nombre' => 'XS', 'etiqueta' => 'XS', 'grupo' => 'Letras', 'orden' => 40],
            ['nombre' => 'S', 'etiqueta' => 'S', 'grupo' => 'Letras', 'orden' => 41],
            ['nombre' => 'M', 'etiqueta' => 'M', 'grupo' => 'Letras', 'orden' => 42],
            ['nombre' => 'L', 'etiqueta' => 'L', 'grupo' => 'Letras', 'orden' => 43],
            ['nombre' => 'XL', 'etiqueta' => 'XL', 'grupo' => 'Letras', 'orden' => 44],
            ['nombre' => 'XXL', 'etiqueta' => 'XXL', 'grupo' => 'Letras', 'orden' => 45],
        ];

        foreach ($tallas as $talla) {
            Talla::updateOrCreate(
                ['nombre' => $talla['nombre']],
                [
                    'etiqueta' => $talla['etiqueta'],
                    'grupo' => $talla['grupo'],
                    'orden' => $talla['orden'],
                    'activo' => true,
                ]
            );
        }
    }
}
