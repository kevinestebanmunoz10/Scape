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
        // Crea la tabla 'departamento'
        Schema::create('departamento', function (Blueprint $table) {
            $table->integer('Codi_Departa'); // Columna entera con el código del departamento
            $table->string('Departamento', 100); // Columna de texto con el nombre del departamento (máx. 100)
            $table->primary('Codi_Departa'); // Define el código del departamento como clave primaria
        });
    }

    // Método que revierte la migración (elimina la tabla)
    public function down(): void
    {
        Schema::dropIfExists('departamento'); // Elimina la tabla 'departamento'
    }
};
