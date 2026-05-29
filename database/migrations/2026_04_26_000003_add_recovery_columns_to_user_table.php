<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('user', function (Blueprint $table) {
            $table->timestamp('recovery_locked_until')->nullable()->after('estado');
            $table->unsignedTinyInteger('recovery_failed_attempts')->default(0)->after('recovery_locked_until');
            $table->boolean('recovery_must_reset_questions')->default(false)->after('recovery_failed_attempts');
        });
    }

    public function down(): void
    {
        Schema::table('user', function (Blueprint $table) {
            $table->dropColumn([
                'recovery_locked_until',
                'recovery_failed_attempts',
                'recovery_must_reset_questions',
            ]);
        });
    }
};
