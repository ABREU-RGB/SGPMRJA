<?php

namespace Database\Seeders;

use App\Models\BordadoUbicacion;
use Illuminate\Database\Seeder;

class BordadoUbicacionSeeder extends Seeder
{
    public function run(): void
    {
        $items = [
            ['nombre' => 'Frontal Izquierdo', 'grupo' => 'Frontal', 'precio_base' => 3.00, 'orden' => 10],
            ['nombre' => 'Frontal Derecho', 'grupo' => 'Frontal', 'precio_base' => 3.00, 'orden' => 20],
            ['nombre' => 'Manga Izquierda', 'grupo' => 'Mangas', 'precio_base' => 3.00, 'orden' => 30],
            ['nombre' => 'Manga Derecha', 'grupo' => 'Mangas', 'precio_base' => 3.00, 'orden' => 40],
            ['nombre' => 'Espaldar', 'grupo' => 'Espalda', 'precio_base' => 5.00, 'orden' => 50],
        ];

        foreach ($items as $item) {
            BordadoUbicacion::updateOrCreate(
                ['nombre' => $item['nombre']],
                [
                    'grupo' => $item['grupo'],
                    'precio_base' => $item['precio_base'],
                    'orden' => $item['orden'],
                    'activo' => true,
                ]
            );
        }
    }
}
