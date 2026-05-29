<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('recovery_attempt', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')
                  ->nullable()
                  ->constrained('user')
                  ->nullOnDelete();
            $table->string('email', 191);
            $table->string('ip', 45)->nullable();
            $table->string('user_agent', 500)->nullable();
            $table->enum('tipo', ['email', 'preguntas']);
            $table->enum('resultado', ['exito', 'fallo', 'bloqueado']);
            $table->timestamp('created_at')->useCurrent();

            $table->index('user_id');
            $table->index('email');
            $table->index('created_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('recovery_attempt');
    }
};
