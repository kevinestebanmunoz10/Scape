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
        // Crea la tabla 'cache'
        Schema::create('cache', function (Blueprint $table) {
            $table->string('key')->primary(); // Clave del caché como clave primaria
            $table->mediumText('value'); // Valor almacenado en el caché
            $table->bigInteger('expiration')->index(); // Marca de tiempo de expiración del caché (con índice)
        });

        // Crea la tabla 'cache_locks'
        Schema::create('cache_locks', function (Blueprint $table) {
            $table->string('key')->primary(); // Clave del bloqueo como clave primaria
            $table->string('owner'); // Propietario del bloqueo (identificador del proceso)
            $table->bigInteger('expiration')->index(); // Marca de tiempo de expiración del bloqueo (con índice)
        });
    }

    /**
     * Reverse the migrations.
     */
    // Método que revierte la migración (elimina las tablas)
    public function down(): void
    {
        Schema::dropIfExists('cache'); // Elimina la tabla 'cache'
        Schema::dropIfExists('cache_locks'); // Elimina la tabla 'cache_locks'
    }
};
