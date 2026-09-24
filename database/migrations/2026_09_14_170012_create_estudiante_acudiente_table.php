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
        // Crea la tabla 'estudiante_acudiente' (relación estudiante - acudiente)
        Schema::create('estudiante_acudiente', function (Blueprint $table) {
            $table->increments('id_estu_acud'); // Columna auto-incremental con el identificador de la relación
            $table->string('Documento_acud', 20); // Documento del acudiente (máx. 20)
            $table->bigInteger('Documento_estu'); // Documento del estudiante
            $table->unsignedInteger('id_parentesco')->nullable(); // ID del parentesco entre ambos (nullable)
            $table->foreign('Documento_acud')->references('Documento_acud')->on('acudiente'); // Llave foránea hacia la tabla 'acudiente'
            $table->foreign('Documento_estu')->references('Documento')->on('usuario'); // Llave foránea hacia la tabla 'usuario'
            $table->foreign('id_parentesco')->references('id_parentesco')->on('parentesco'); // Llave foránea hacia la tabla 'parentesco'
        });
    }

    // Método que revierte la migración (elimina la tabla)
    public function down(): void
    {
        Schema::dropIfExists('estudiante_acudiente'); // Elimina la tabla 'estudiante_acudiente'
    }
};
