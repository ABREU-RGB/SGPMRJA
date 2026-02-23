<?php

namespace Database\Seeders;

use App\Models\Color;
use Illuminate\Database\Seeder;

class ColorSeeder extends Seeder
{
    /**
     * Catálogo de ~30 colores estándar de la industria textil/manufacturera.
     * Los HEX son REFERENCIALES — el tono físico de cada material varía.
     */
    public function run(): void
    {
        $colores = [
            // ── Básicos ──────────────────────────────────────────────
            ['nombre' => 'Blanco', 'hex_referencial' => '#FFFFFF', 'grupo' => 'Básicos'],
            ['nombre' => 'Negro', 'hex_referencial' => '#1C1C1C', 'grupo' => 'Básicos'],
            ['nombre' => 'Gris Claro', 'hex_referencial' => '#C0C0C0', 'grupo' => 'Básicos'],
            ['nombre' => 'Gris Oscuro', 'hex_referencial' => '#5A5A5A', 'grupo' => 'Básicos'],
            ['nombre' => 'Gris Jaspeado', 'hex_referencial' => '#9E9E9E', 'grupo' => 'Básicos'],

            // ── Azules ───────────────────────────────────────────────
            ['nombre' => 'Azul Marino', 'hex_referencial' => '#1B3A5C', 'grupo' => 'Azules'],
            ['nombre' => 'Azul Royal', 'hex_referencial' => '#2E5DA8', 'grupo' => 'Azules'],
            ['nombre' => 'Azul Cielo', 'hex_referencial' => '#87CEEB', 'grupo' => 'Azules'],
            ['nombre' => 'Azul Turquesa', 'hex_referencial' => '#40B5AD', 'grupo' => 'Azules'],
            ['nombre' => 'Azul Petróleo', 'hex_referencial' => '#1A5276', 'grupo' => 'Azules'],

            // ── Rojos y Cálidos ──────────────────────────────────────
            ['nombre' => 'Rojo', 'hex_referencial' => '#C0392B', 'grupo' => 'Rojos y Cálidos'],
            ['nombre' => 'Rojo Vino', 'hex_referencial' => '#722F37', 'grupo' => 'Rojos y Cálidos'],
            ['nombre' => 'Naranja', 'hex_referencial' => '#E67E22', 'grupo' => 'Rojos y Cálidos'],
            ['nombre' => 'Coral', 'hex_referencial' => '#E8735A', 'grupo' => 'Rojos y Cálidos'],
            ['nombre' => 'Amarillo', 'hex_referencial' => '#F1C40F', 'grupo' => 'Rojos y Cálidos'],

            // ── Verdes ───────────────────────────────────────────────
            ['nombre' => 'Verde Oscuro', 'hex_referencial' => '#1E5631', 'grupo' => 'Verdes'],
            ['nombre' => 'Verde Oliva', 'hex_referencial' => '#6B8E23', 'grupo' => 'Verdes'],
            ['nombre' => 'Verde Menta', 'hex_referencial' => '#98D8C8', 'grupo' => 'Verdes'],
            ['nombre' => 'Verde Botella', 'hex_referencial' => '#0B5345', 'grupo' => 'Verdes'],

            // ── Pasteles ─────────────────────────────────────────────
            ['nombre' => 'Rosa Pastel', 'hex_referencial' => '#F5B7C1', 'grupo' => 'Pasteles'],
            ['nombre' => 'Celeste', 'hex_referencial' => '#AED6F1', 'grupo' => 'Pasteles'],
            ['nombre' => 'Lila', 'hex_referencial' => '#C39BD3', 'grupo' => 'Pasteles'],
            ['nombre' => 'Melocotón', 'hex_referencial' => '#F5CBA7', 'grupo' => 'Pasteles'],
            ['nombre' => 'Lavanda', 'hex_referencial' => '#D7BDE2', 'grupo' => 'Pasteles'],

            // ── Tierra y Neutros ─────────────────────────────────────
            ['nombre' => 'Beige', 'hex_referencial' => '#F5DEB3', 'grupo' => 'Tierra y Neutros'],
            ['nombre' => 'Caqui', 'hex_referencial' => '#C3B091', 'grupo' => 'Tierra y Neutros'],
            ['nombre' => 'Marrón', 'hex_referencial' => '#6E3B23', 'grupo' => 'Tierra y Neutros'],
            ['nombre' => 'Café', 'hex_referencial' => '#4E342E', 'grupo' => 'Tierra y Neutros'],
            ['nombre' => 'Crema', 'hex_referencial' => '#FFFDD0', 'grupo' => 'Tierra y Neutros'],

            // ── Especiales ───────────────────────────────────────────
            ['nombre' => 'Fucsia', 'hex_referencial' => '#C71585', 'grupo' => 'Especiales'],
            ['nombre' => 'Morado', 'hex_referencial' => '#6C3483', 'grupo' => 'Especiales'],
            ['nombre' => 'Dorado', 'hex_referencial' => '#D4A017', 'grupo' => 'Especiales'],
        ];

        foreach ($colores as $color) {
            Color::firstOrCreate(
                ['nombre' => $color['nombre']],
                [
                    'hex_referencial' => $color['hex_referencial'],
                    'grupo' => $color['grupo'],
                    'activo' => true,
                ]
            );
        }
    }
}
