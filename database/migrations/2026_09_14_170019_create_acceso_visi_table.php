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
        // Crea la tabla 'acceso_visi' (accesos y salidas de visitantes)
        Schema::create('acceso_visi', function (Blueprint $table) {
            $table->increments('id_acces_visi'); // Columna auto-incremental con el identificador del acceso
            $table->dateTime('f_ingreso'); // Fecha y hora de ingreso del visitante
            $table->dateTime('f_salida')->nullable(); // Fecha y hora de salida del visitante (nullable)
            $table->string('Documento_visi', 20)->nullable(); // Documento del visitante que accede (nullable) (máx. 20)
            $table->unsignedInteger('id_Estado_visi')->nullable(); // ID del estado del acceso (nullable)
            $table->string('Lugar', 150)->nullable(); // Lugar al que se dirige el visitante (nullable) (máx. 150)
            $table->foreign('Documento_visi')->references('Docu_visi')->on('visitantes'); // Llave foránea hacia la tabla 'visitantes'
            $table->foreign('id_Estado_visi')->references('id_estado')->on('estado'); // Llave foránea hacia la tabla 'estado'
        });
    }

    // Método que revierte la migración (elimina la tabla)
    public function down(): void
    {
        Schema::dropIfExists('acceso_visi'); // Elimina la tabla 'acceso_visi'
    }
};
