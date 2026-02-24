<?php

namespace App\Services;

class BordadoPricingService
{
    /**
     * Normaliza el arreglo de bordados de una línea de producto.
     */
    public function normalizeBordados(array $item): array
    {
        $llevaBordado = !empty($item['lleva_bordado']) && (int) $item['lleva_bordado'] === 1;
        if (!$llevaBordado || empty($item['bordados']) || !is_array($item['bordados'])) {
            return [];
        }

        $bordados = [];
        foreach ($item['bordados'] as $index => $bordado) {
            $bordados[] = [
                'ubicacion_bordado_id' => $bordado['ubicacion_bordado_id'] ?? null,
                'nombre_aplicado' => trim((string) ($bordado['nombre_aplicado'] ?? '')),
                'nombre_logo' => trim((string) ($bordado['nombre_logo'] ?? $item['nombre_logo'] ?? '')),
                'es_personalizada' => (bool) ($bordado['es_personalizada'] ?? false),
                'cantidad' => max(1, (int) ($bordado['cantidad'] ?? 1)),
                'precio_aplicado' => (float) ($bordado['precio_aplicado'] ?? 0),
                'orden' => (int) $index,
            ];
        }

        return $bordados;
    }

    /**
     * Calcula el recargo unitario total por bordado.
     */
    public function calcularRecargoBordadoUnitario(array $bordados): float
    {
        $recargo = 0;
        foreach ($bordados as $bordado) {
            $precio = (float) ($bordado['precio_aplicado'] ?? 0);
            $cantidad = max(1, (int) ($bordado['cantidad'] ?? 1));
            $recargo += ($precio * $cantidad);
        }

        return $recargo;
    }

    /**
     * Calcula el precio unitario final (base + recargo bordado).
     */
    public function calcularPrecioUnitarioFinal(float $precioBase, array $bordados): float
    {
        return $precioBase + $this->calcularRecargoBordadoUnitario($bordados);
    }

    /**
     * Resuelve el nombre_logo legado para detalle padre como resumen.
     */
    public function resolverNombreLogoDetalle(array $item, array $bordados): ?string
    {
        $logos = collect($bordados)
            ->map(fn($bordado) => trim((string) ($bordado['nombre_logo'] ?? '')))
            ->filter()
            ->unique()
            ->values();

        if ($logos->isNotEmpty()) {
            return mb_substr($logos->implode(', '), 0, 100);
        }

        $legacy = trim((string) ($item['nombre_logo'] ?? ''));
        return $legacy !== '' ? mb_substr($legacy, 0, 100) : null;
    }
}
