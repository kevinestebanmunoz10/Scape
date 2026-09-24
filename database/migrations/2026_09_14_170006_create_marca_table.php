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
        // Crea la tabla 'marca'
        Schema::create('marca', function (Blueprint $table) {
            $table->increments('id_marca'); // Columna auto-incremental con el identificador de la marca
            $table->string('marca', 50); // Columna de texto con el nombre de la marca (máx. 50)
        });
    }

    // Método que revierte la migración (elimina la tabla)
    public function down(): void
    {
        Schema::dropIfExists('marca'); // Elimina la tabla 'marca'
    }
};
