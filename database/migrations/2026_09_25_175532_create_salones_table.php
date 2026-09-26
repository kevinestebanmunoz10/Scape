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
        // Crea la tabla 'salones'
        Schema::create('salones', function (Blueprint $table) {
            $table->increments('id_salon'); // Columna auto-incremental con el identificador del salón
            $table->string('codigo', 20)->unique(); // Columna de texto con el código del salón, único por sede (máx. 20)
            $table->unsignedInteger('capacidad')->default(0); // Número de estudiantes que admite el salón (0 por defecto)
            $table->unsignedInteger('id_Estado')->default(1); // ID del estado del salón (1 = activo por defecto)
            $table->foreign('id_Estado')->references('id_estado')->on('estado'); // Llave foránea hacia la tabla 'estado'
        });
    }

    // Método que revierte la migración (elimina la tabla)
    public function down(): void
    {
        Schema::dropIfExists('salones'); // Elimina la tabla 'salones'
    }
};
