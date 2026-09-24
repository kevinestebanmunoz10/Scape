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
        // Crea la tabla 'acceso_usu' (accesos y salidas de usuarios)
        Schema::create('acceso_usu', function (Blueprint $table) {
            $table->increments('id_acces_usu'); // Columna auto-incremental con el identificador del acceso
            $table->dateTime('f_ingreso'); // Fecha y hora de ingreso del usuario
            $table->dateTime('f_salida')->nullable(); // Fecha y hora de salida del usuario (nullable)
            $table->bigInteger('Documento_acces')->nullable(); // Documento del usuario que accede (nullable)
            $table->unsignedInteger('id_Estado_usu')->nullable(); // ID del estado del acceso (nullable)
            $table->foreign('Documento_acces')->references('Documento')->on('usuario'); // Llave foránea hacia la tabla 'usuario'
            $table->foreign('id_Estado_usu')->references('id_estado')->on('estado'); // Llave foránea hacia la tabla 'estado'
        });
    }

    // Método que revierte la migración (elimina la tabla)
    public function down(): void
    {
        Schema::dropIfExists('acceso_usu'); // Elimina la tabla 'acceso_usu'
    }
};
