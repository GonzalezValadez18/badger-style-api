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
        Schema::create('users', function (Blueprint $table) {
            $table->id(); // Campo 'id' (BIGINT, UNSIGNED, AUTO_INCREMENT)
            $table->string('name'); // Campo 'name' para el nombre del usuario
            $table->string('email')->unique(); // Campo 'email', debe ser único
            $table->string('username')->unique()->nullable(); // Campo 'username', único y opcional
            $table->timestamp('email_verified_at')->nullable(); // Para verificación de email (estándar de Laravel)
            $table->string('password'); // Campo para la contraseña hasheada
            $table->rememberToken(); // Para la función "recordarme" (estándar de Laravel)
            $table->timestamps(); // Campos 'created_at' y 'updated_at'
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
