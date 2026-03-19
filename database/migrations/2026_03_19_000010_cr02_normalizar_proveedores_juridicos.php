<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * CR-02 — Normalizar proveedores jurídicos al sistema Persona
 *
 * Migra razon_social, rif, telefono, email, direccion de la tabla proveedor
 * al sistema normalizado persona + telefono + direccion.
 *
 * Después del migrate, TODOS los proveedores (naturales y jurídicos) apuntan
 * a persona_id y los campos duplicados se eliminan.
 */
return new class extends Migration
{
    public function up(): void
    {
        // 1. Migrar datos de proveedores jurídicos al sistema Persona
        $juridicos = DB::table('proveedor')
            ->where('tipo_proveedor', 'juridico')
            ->whereNull('deleted_at')
            ->get();

        foreach ($juridicos as $prov) {
            // Parsear RIF: 'J-1231321' → tipo_documento='J-', documento_identidad='1231321'
            $tipoDoc = 'J-';
            $docId = $prov->rif;
            if (preg_match('/^(V-|J-|E-|G-)(.+)$/', $prov->rif, $matches)) {
                $tipoDoc = $matches[1];
                $docId = $matches[2];
            }

            $personaId = DB::table('persona')->insertGetId([
                'nombre' => $prov->razon_social,
                'apellido' => '',
                'tipo_documento' => $tipoDoc,
                'documento_identidad' => $docId,
                'email' => $prov->email,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            if (!empty($prov->telefono)) {
                DB::table('telefono')->insert([
                    'persona_id' => $personaId,
                    'numero' => $prov->telefono,
                    'tipo' => 'trabajo',
                    'es_principal' => true,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }

            if (!empty($prov->direccion)) {
                DB::table('direccion')->insert([
                    'persona_id' => $personaId,
                    'direccion' => $prov->direccion,
                    'tipo' => 'trabajo',
                    'es_principal' => true,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }

            DB::table('proveedor')
                ->where('id', $prov->id)
                ->update(['persona_id' => $personaId]);
        }

        // 2. Eliminar columnas duplicadas
        Schema::table('proveedor', function (Blueprint $table) {
            $table->dropColumn(['razon_social', 'rif', 'direccion', 'telefono', 'email']);
        });
    }

    public function down(): void
    {
        // Restaurar columnas
        Schema::table('proveedor', function (Blueprint $table) {
            $table->string('razon_social', 100)->nullable()->after('persona_id');
            $table->string('rif', 15)->nullable()->after('razon_social');
            $table->string('direccion', 191)->nullable()->after('rif');
            $table->string('telefono', 20)->nullable()->after('direccion');
            $table->string('email', 191)->nullable()->after('telefono');
        });

        // Restaurar datos de jurídicos desde persona
        $juridicos = DB::table('proveedor')
            ->where('tipo_proveedor', 'juridico')
            ->whereNull('deleted_at')
            ->get();

        foreach ($juridicos as $prov) {
            if (!$prov->persona_id) continue;

            $persona = DB::table('persona')->find($prov->persona_id);
            if (!$persona) continue;

            $telefono = DB::table('telefono')
                ->where('persona_id', $prov->persona_id)
                ->where('es_principal', true)
                ->first();

            $direccion = DB::table('direccion')
                ->where('persona_id', $prov->persona_id)
                ->where('es_principal', true)
                ->first();

            DB::table('proveedor')->where('id', $prov->id)->update([
                'razon_social' => $persona->nombre,
                'rif' => $persona->tipo_documento . $persona->documento_identidad,
                'telefono' => $telefono ? $telefono->numero : null,
                'email' => $persona->email,
                'direccion' => $direccion ? $direccion->direccion : null,
                'persona_id' => null,
            ]);

            // Limpiar persona/telefono/direccion creados en up()
            DB::table('telefono')->where('persona_id', $prov->persona_id)->delete();
            DB::table('direccion')->where('persona_id', $prov->persona_id)->delete();
            DB::table('persona')->where('id', $prov->persona_id)->delete();
        }
    }
};
