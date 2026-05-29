<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Agregar columnas FK nullable
        Schema::table('empleado', function (Blueprint $table) {
            $table->unsignedBigInteger('departamento_id')->nullable()->after('departamento');
            $table->unsignedBigInteger('cargo_id')->nullable()->after('cargo');
        });

        // 2. Poblar tabla departamento con valores únicos existentes
        $departamentos = DB::table('empleado')
            ->whereNotNull('departamento')
            ->where('departamento', '!=', '')
            ->pluck('departamento')
            ->unique()
            ->values();

        foreach ($departamentos as $nombre) {
            DB::table('departamento')->insertOrIgnore([
                'nombre'     => trim($nombre),
                'activo'     => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        // 3. Poblar tabla cargo con combinaciones únicas (cargo, departamento)
        $combos = DB::table('empleado')
            ->whereNotNull('cargo')->where('cargo', '!=', '')
            ->whereNotNull('departamento')->where('departamento', '!=', '')
            ->select('cargo', 'departamento')
            ->distinct()
            ->get();

        foreach ($combos as $combo) {
            $deptId = DB::table('departamento')->where('nombre', trim($combo->departamento))->value('id');
            if ($deptId) {
                DB::table('cargo')->insertOrIgnore([
                    'nombre'         => trim($combo->cargo),
                    'departamento_id' => $deptId,
                    'activo'         => 1,
                    'created_at'     => now(),
                    'updated_at'     => now(),
                ]);
            }
        }

        // 4. Cargos huérfanos (sin departamento) → "Sin clasificar"
        $huerfanos = DB::table('empleado')
            ->whereNotNull('cargo')->where('cargo', '!=', '')
            ->where(function ($q) {
                $q->whereNull('departamento')->orWhere('departamento', '');
            })
            ->pluck('cargo')
            ->unique();

        if ($huerfanos->isNotEmpty()) {
            DB::table('departamento')->insertOrIgnore([
                'nombre'     => 'Sin clasificar',
                'activo'     => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
            $sinClasificarId = DB::table('departamento')->where('nombre', 'Sin clasificar')->value('id');

            foreach ($huerfanos as $cargoNombre) {
                DB::table('cargo')->insertOrIgnore([
                    'nombre'         => trim($cargoNombre),
                    'departamento_id' => $sinClasificarId,
                    'activo'         => 1,
                    'created_at'     => now(),
                    'updated_at'     => now(),
                ]);
            }
        }

        // 5. Actualizar cada empleado con los IDs correspondientes
        $empleados = DB::table('empleado')->get();
        foreach ($empleados as $emp) {
            $deptNombre  = trim($emp->departamento ?? '');
            $cargoNombre = trim($emp->cargo ?? '');

            $deptId = $deptNombre !== ''
                ? DB::table('departamento')->where('nombre', $deptNombre)->value('id')
                : null;

            if (!$deptId) {
                $deptId = DB::table('departamento')->where('nombre', 'Sin clasificar')->value('id');
            }

            $cargoId = ($cargoNombre !== '' && $deptId)
                ? DB::table('cargo')
                    ->where('nombre', $cargoNombre)
                    ->where('departamento_id', $deptId)
                    ->value('id')
                : null;

            DB::table('empleado')->where('id', $emp->id)->update([
                'departamento_id' => $deptId,
                'cargo_id'        => $cargoId,
            ]);
        }

        // 6. Agregar constraints FK
        Schema::table('empleado', function (Blueprint $table) {
            $table->foreign('departamento_id')->references('id')->on('departamento')->restrictOnDelete();
            $table->foreign('cargo_id')->references('id')->on('cargo')->restrictOnDelete();
        });

        // 7. Eliminar columnas varchar antiguas
        Schema::table('empleado', function (Blueprint $table) {
            $table->dropColumn(['cargo', 'departamento']);
        });
    }

    public function down(): void
    {
        // Restaurar columnas varchar
        Schema::table('empleado', function (Blueprint $table) {
            $table->string('cargo', 100)->nullable()->after('cargo_id');
            $table->string('departamento', 100)->nullable()->after('departamento_id');
        });

        // Restaurar datos de texto desde las tablas FK
        $empleados = DB::table('empleado')->get();
        foreach ($empleados as $emp) {
            $cargoNombre = $emp->cargo_id
                ? DB::table('cargo')->where('id', $emp->cargo_id)->value('nombre')
                : null;
            $deptoNombre = $emp->departamento_id
                ? DB::table('departamento')->where('id', $emp->departamento_id)->value('nombre')
                : null;

            DB::table('empleado')->where('id', $emp->id)->update([
                'cargo'       => $cargoNombre,
                'departamento' => $deptoNombre,
            ]);
        }

        // Eliminar FKs y columnas nuevas
        Schema::table('empleado', function (Blueprint $table) {
            $table->dropForeign(['departamento_id']);
            $table->dropForeign(['cargo_id']);
            $table->dropColumn(['departamento_id', 'cargo_id']);
        });

        Schema::dropIfExists('cargo');
        Schema::dropIfExists('departamento');
    }
};
