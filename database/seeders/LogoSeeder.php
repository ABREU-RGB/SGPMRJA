<?php

namespace Database\Seeders;

use App\Models\Logo;
use Illuminate\Database\Seeder;

class LogoSeeder extends Seeder
{
    /**
     * Pega aquí la lista de nombres de archivo (.emb) de tu carpeta MEGA.
     * Ejemplo: 'LOGO_EMPRESA_ABC.emb', 'ESCUDO_CLUB_XYZ.emb'
     *
     * Cada entrada generará un logo con:
     *  - original_filename: el nombre de archivo tal cual
     *  - name: el nombre sin extensión (usado en el formulario de cotización)
     */
    public function run(): void
    {
        $logos = [
            'Colegio Angel de la Guarda.emb',
            'Asoportuguesa Corp.emb',
            'Los Caminos Hacienda.emb',
            'PAICA Alimentos.emb',
            'Alcaldia Municipal.emb',
            'Banco Provincial S.A.emb',
            'Coca-Cola Classic.emb',
            'Distribuidora El Faro.emb',
            'Escuela de Futbol.emb',
            'Farmacia Express.emb',
            'Gimnasio Iron Body.emb',
            'Hotel Kristoff.emb',
            'Iglesia San Juan.emb',
            'Inversiones Polar.emb',
            'Logistica Global.emb',
            'Panaderia La Espiga.emb',
            'Restaurante El Meson.emb',
            'Supermercado Garzon.emb',
            'Transporte Rapido.emb',
            'Universidad Central.emb',
        ];

        foreach ($logos as $filename) {
            Logo::firstOrCreate(
                ['original_filename' => $filename],
                ['name' => pathinfo($filename, PATHINFO_FILENAME)]
            );
        }
    }
}
