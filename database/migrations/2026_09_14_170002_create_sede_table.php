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
        // Crea la tabla 'sede'
        Schema::create('sede', function (Blueprint $table) {
            $table->integer('Nit'); // Columna entera con el NIT como identificador de la sede
            $table->string('nombre', 150); // Columna de texto con el nombre de la sede (máx. 150)
            $table->integer('cod_postal'); // Código postal de la ciudad donde se encuentra la sede
            $table->primary('Nit'); // Define el NIT como clave primaria
            $table->foreign('cod_postal')->references('cod_Postal')->on('ciudad'); // Llave foránea hacia la tabla 'ciudad'
        });
    }

    // Método que revierte la migración (elimina la tabla)
    public function down(): void
    {
        Schema::dropIfExists('sede'); // Elimina la tabla 'sede'
    }
};
