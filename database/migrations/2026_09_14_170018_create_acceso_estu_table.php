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
        // Crea la tabla 'acceso_estu' (accesos y salidas de estudiantes)
        Schema::create('acceso_estu', function (Blueprint $table) {
            $table->increments('id_acces_estu'); // Columna auto-incremental con el identificador del acceso
            $table->dateTime('f_ingreso'); // Fecha y hora de ingreso del estudiante
            $table->dateTime('f_salida')->nullable(); // Fecha y hora de salida del estudiante (nullable)
            $table->bigInteger('Documento_estu')->nullable(); // Documento del estudiante que accede (nullable)
            $table->unsignedInteger('id_autorizacion')->nullable(); // ID de la autorización que permite el acceso (nullable)
            $table->foreign('Documento_estu')->references('Documento')->on('usuario'); // Llave foránea hacia la tabla 'usuario'
            $table->foreign('id_autorizacion')->references('id_autorizacion')->on('autorizacion'); // Llave foránea hacia la tabla 'autorizacion'
        });
    }

    // Método que revierte la migración (elimina la tabla)
    public function down(): void
    {
        Schema::dropIfExists('acceso_estu'); // Elimina la tabla 'acceso_estu'
    }
};
