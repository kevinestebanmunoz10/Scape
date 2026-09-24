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
        // Crea la tabla 'acceso_equi' (accesos y salidas de equipos)
        Schema::create('acceso_equi', function (Blueprint $table) {
            $table->increments('id_regi_equip'); // Columna auto-incremental con el identificador del registro
            $table->dateTime('f_entrada'); // Fecha y hora de entrada del equipo
            $table->dateTime('f_salida')->nullable(); // Fecha y hora de salida del equipo (nullable)
            $table->string('serial_equi', 50); // Serial del equipo registrado (máx. 50)
            $table->bigInteger('Documento'); // Documento del usuario relacionado con el acceso
            $table->foreign('serial_equi')->references('serial_equi')->on('equipo'); // Llave foránea hacia la tabla 'equipo'
            $table->foreign('Documento')->references('Documento')->on('usuario'); // Llave foránea hacia la tabla 'usuario'
        });
    }

    // Método que revierte la migración (elimina la tabla)
    public function down(): void
    {
        Schema::dropIfExists('acceso_equi'); // Elimina la tabla 'acceso_equi'
    }
};
