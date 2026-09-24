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
        // Crea la tabla 'ciudad'
        Schema::create('ciudad', function (Blueprint $table) {
            $table->integer('cod_Postal'); // Columna entera con el código postal como identificador de la ciudad
            $table->string('Ciudad', 100); // Columna de texto con el nombre de la ciudad (máx. 100)
            $table->integer('Codi_Departa'); // Código del departamento al que pertenece la ciudad
            $table->primary('cod_Postal'); // Define el código postal como clave primaria
            $table->foreign('Codi_Departa')->references('Codi_Departa')->on('departamento'); // Llave foránea hacia la tabla 'departamento'
        });
    }

    // Método que revierte la migración (elimina la tabla)
    public function down(): void
    {
        Schema::dropIfExists('ciudad'); // Elimina la tabla 'ciudad'
    }
};
