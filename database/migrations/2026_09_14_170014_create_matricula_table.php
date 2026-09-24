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
        // Crea la tabla 'matricula'
        Schema::create('matricula', function (Blueprint $table) {
            $table->increments('numero_matri'); // Columna auto-incremental con el número de matrícula
            $table->string('grado', 20); // Columna de texto con el grado del estudiante (máx. 20)
            $table->date('fecha_matri'); // Columna de tipo fecha con la fecha de matrícula
            $table->bigInteger('Documento_matri'); // Documento del estudiante matriculado
            $table->string('Jornada', 20)->nullable(); // Columna de texto con la jornada (nullable) (máx. 20)
            $table->string('Salon', 50)->nullable(); // Columna de texto con el salón asignado (nullable) (máx. 50)
            $table->integer('Nit')->nullable(); // NIT de la sede donde se matricula (nullable)
            $table->foreign('Documento_matri')->references('Documento')->on('usuario'); // Llave foránea hacia la tabla 'usuario'
            $table->foreign('Nit')->references('Nit')->on('sede'); // Llave foránea hacia la tabla 'sede'
        });
    }

    // Método que revierte la migración (elimina la tabla)
    public function down(): void
    {
        Schema::dropIfExists('matricula'); // Elimina la tabla 'matricula'
    }
};
