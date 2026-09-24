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
        // Crea la tabla 'acudiente'
        Schema::create('acudiente', function (Blueprint $table) {
            $table->string('Documento_acud', 20); // Columna con el documento del acudiente como identificador (máx. 20)
            $table->string('nombre', 100); // Columna de texto con el nombre del acudiente (máx. 100)
            $table->string('tel_acu', 20); // Columna de texto con el teléfono principal del acudiente (máx. 20)
            $table->string('tel_acu2', 20)->nullable(); // Columna de texto con el segundo teléfono (nullable) (máx. 20)
            $table->string('Direcccion_acu', 150)->nullable(); // Columna de texto con la dirección del acudiente (nullable) (máx. 150)
            $table->string('Correo_acud', 100); // Columna de texto con el correo del acudiente (máx. 100)
            $table->primary('Documento_acud'); // Define el documento del acudiente como clave primaria
        });
    }

    // Método que revierte la migración (elimina la tabla)
    public function down(): void
    {
        Schema::dropIfExists('acudiente'); // Elimina la tabla 'acudiente'
    }
};
