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
        // Crea la tabla 'autorizacion'
        Schema::create('autorizacion', function (Blueprint $table) {
            $table->increments('id_autorizacion'); // Columna auto-incremental con el identificador de la autorización
            $table->string('Permiso', 100); // Columna de texto con el nombre del permiso (máx. 100)
            $table->text('Descripcion')->nullable(); // Columna de texto largo con la descripción del permiso (nullable)
            $table->date('Fecha')->nullable(); // Columna de tipo fecha (nullable)
            $table->unsignedInteger('id_rol'); // ID del rol al que aplica la autorización
            $table->unsignedInteger('id_estu_acud')->nullable(); // ID de la relación estudiante-acudiente (nullable)
            $table->bigInteger('Documento_auto')->nullable(); // Documento del usuario que autoriza (nullable: pendiente de autorización)
            $table->foreign('id_rol')->references('id_rol')->on('rol'); // Llave foránea hacia la tabla 'rol'
            $table->foreign('id_estu_acud')->references('id_estu_acud')->on('estudiante_acudiente'); // Llave foránea hacia la tabla 'estudiante_acudiente'
            $table->foreign('Documento_auto')->references('Documento')->on('usuario'); // Llave foránea hacia la tabla 'usuario'
        });
    }

    // Método que revierte la migración (elimina la tabla)
    public function down(): void
    {
        Schema::dropIfExists('autorizacion'); // Elimina la tabla 'autorizacion'
    }
};
