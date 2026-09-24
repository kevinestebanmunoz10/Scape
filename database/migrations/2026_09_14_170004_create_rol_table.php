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
    // Método que ejecuta la migración (crea la tabla)
    public function up(): void
    {
        // Crea la tabla 'rol'
        Schema::create('rol', function (Blueprint $table) {
            $table->increments('id_rol'); // Columna auto-incremental con el identificador del rol
            $table->string('rol', 50); // Columna de texto con el nombre del rol (máx. 50)
        });
    }

    // Método que revierte la migración (elimina la tabla)
    public function down(): void
    {
        Schema::dropIfExists('rol'); // Elimina la tabla 'rol'
    }
};
