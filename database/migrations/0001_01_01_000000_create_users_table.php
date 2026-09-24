<?php

// Apertura del script PHP

// Clase base para las migraciones de Laravel
use Illuminate\Database\Migrations\Migration;
// Blueprint permite definir la estructura de las tablas
use Illuminate\Database\Schema\Blueprint;
// Fachada de esquema para crear y eliminar tablas
use Illuminate\Support\Facades\Schema;

// Define la clase anónima de la migración que Laravel ejecutará
return new class extends Migration
{
    /**
     * Run the migrations.
     */
    // Método que ejecuta la migración (crea las tablas)
    public function up(): void
    {
        // Crea la tabla 'users'
        Schema::create('users', function (Blueprint $table) {
            $table->id(); // Columna clave primaria auto-incremental
            $table->string('name'); // Columna de texto con el nombre del usuario
            $table->string('email')->unique(); // Columna de correo electrónico única
            $table->timestamp('email_verified_at')->nullable(); // Marca de tiempo para verificación del correo (nullable)
            $table->string('password'); // Columna para la contraseña (hasheada)
            $table->rememberToken(); // Columna para el token "remember me"
            $table->timestamps(); // Columnas created_at y updated_at
        });

        // Crea la tabla 'password_reset_tokens'
        Schema::create('password_reset_tokens', function (Blueprint $table) {
            $table->string('email')->primary(); // Correo como clave primaria
            $table->string('token'); // Token de restablecimiento de contraseña
            $table->string('code', 255)->nullable()->after('token'); // Código de verificación adicional (nullable), después de 'token'
            $table->timestamp('expires_at')->nullable()->after('code'); // Fecha de expiración del token (nullable), después de 'code'
            $table->timestamp('created_at')->nullable(); // Fecha de creación del token (nullable)
        });

        // Crea la tabla 'sessions'
        Schema::create('sessions', function (Blueprint $table) {
            $table->string('id')->primary(); // Identificador de la sesión como clave primaria
            $table->foreignId('user_id')->nullable()->index(); // ID del usuario dueño de la sesión (nullable, con índice)
            $table->string('ip_address', 45)->nullable(); // Dirección IP desde la que se conectó (nullable)
            $table->text('user_agent')->nullable(); // Información del navegador/dispositivo del usuario (nullable)
            $table->longText('payload'); // Datos serializados de la sesión
            $table->integer('last_activity')->index(); // Marca de tiempo de la última actividad (con índice)
        });
    }

    /**
     * Reverse the migrations.
     */
    // Método que revierte la migración (elimina las tablas)
    public function down(): void
    {
        Schema::dropIfExists('users'); // Elimina la tabla 'users'
        Schema::dropIfExists('password_reset_tokens'); // Elimina la tabla 'password_reset_tokens'
        Schema::dropIfExists('sessions'); // Elimina la tabla 'sessions'
    }
};
