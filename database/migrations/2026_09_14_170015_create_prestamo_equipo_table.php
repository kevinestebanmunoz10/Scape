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
        // Crea la tabla 'prestamo_equipo' (préstamos de equipos)
        Schema::create('prestamo_equipo', function (Blueprint $table) {
            $table->increments('id_Prestamo_equi'); // Columna auto-incremental con el identificador del préstamo
            $table->dateTime('f_Prestamo'); // Fecha y hora en que se realizó el préstamo
            $table->dateTime('f_devolucion')->nullable(); // Fecha y hora de devolución (nullable)
            $table->string('motivo', 255)->nullable(); // Motivo del préstamo (nullable) (máx. 255)
            $table->bigInteger('Documento_pres'); // Documento del usuario que solicita el préstamo
            $table->string('serial_equi', 50); // Serial del equipo prestado (máx. 50)
            $table->unsignedInteger('id_estado'); // ID del estado del préstamo
            $table->foreign('Documento_pres')->references('Documento')->on('usuario'); // Llave foránea hacia la tabla 'usuario'
            $table->foreign('serial_equi')->references('serial_equi')->on('equipo'); // Llave foránea hacia la tabla 'equipo'
            $table->foreign('id_estado')->references('id_estado')->on('estado'); // Llave foránea hacia la tabla 'estado'
        });
    }

    // Método que revierte la migración (elimina la tabla)
    public function down(): void
    {
        Schema::dropIfExists('prestamo_equipo'); // Elimina la tabla 'prestamo_equipo'
    }
};
