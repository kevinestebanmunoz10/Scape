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
        // Crea la tabla 'parentesco'
        Schema::create('parentesco', function (Blueprint $table) {
            $table->increments('id_parentesco'); // Columna auto-incremental con el identificador del parentesco
            $table->text('parentesco')->nullable(); // Columna de texto con la descripción del parentesco (nullable)
            $table->unsignedInteger('Estado_paren')->nullable(); // ID del estado del parentesco (nullable)
            $table->foreign('Estado_paren')->references('id_estado')->on('estado'); // Llave foránea hacia la tabla 'estado'
        });
    }

    // Método que revierte la migración (elimina la tabla)
    public function down(): void
    {
        Schema::dropIfExists('parentesco'); // Elimina la tabla 'parentesco'
    }
};
