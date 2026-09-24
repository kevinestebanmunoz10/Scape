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
        // Crea la tabla 'equipo'
        Schema::create('equipo', function (Blueprint $table) {
            $table->string('serial_equi', 50); // Columna con el serial del equipo como identificador (máx. 50)
            $table->unsignedInteger('id_t_equip'); // ID del tipo de equipo asignado
            $table->unsignedInteger('id_Marca'); // ID de la marca del equipo
            $table->string('Color', 30)->nullable(); // Columna de texto con el color del equipo (nullable) (máx. 30)
            $table->string('imagen', 255)->nullable(); // Ruta de la imagen del equipo (nullable) (máx. 255)
            $table->bigInteger('Documento')->nullable(); // Documento del usuario responsable del equipo (nullable)
            $table->primary('serial_equi'); // Define el serial como clave primaria
            $table->foreign('id_t_equip')->references('id_t_equip')->on('tipo_equipo'); // Llave foránea hacia la tabla 'tipo_equipo'
            $table->foreign('id_Marca')->references('id_marca')->on('marca'); // Llave foránea hacia la tabla 'marca'
            $table->foreign('Documento')->references('Documento')->on('usuario'); // Llave foránea hacia la tabla 'usuario'
        });
    }

    // Método que revierte la migración (elimina la tabla)
    public function down(): void
    {
        Schema::dropIfExists('equipo'); // Elimina la tabla 'equipo'
    }
};
