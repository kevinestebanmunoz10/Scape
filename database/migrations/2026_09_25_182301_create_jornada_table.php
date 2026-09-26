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
        // Crea la tabla 'jornada'
        Schema::create('jornada', function (Blueprint $table) {
            $table->increments('id_jornada'); // Columna auto-incremental con el identificador de la jornada
            $table->string('jornada', 20)->unique(); // Nombre de la jornada escolar, único en el catálogo (máx. 20)
        });
    }

    // Método que revierte la migración (elimina la tabla)
    public function down(): void
    {
        Schema::dropIfExists('jornada'); // Elimina la tabla 'jornada'
    }
};
