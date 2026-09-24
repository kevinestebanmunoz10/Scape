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
        // Crea la tabla 'visitantes'
        Schema::create('visitantes', function (Blueprint $table) {
            $table->string('Docu_visi', 20); // Columna con el documento del visitante como identificador (máx. 20)
            $table->string('Nom_visi', 100); // Columna de texto con el nombre del visitante (máx. 100)
            $table->string('tel_visi', 20); // Columna de texto con el teléfono del visitante (máx. 20)
            $table->string('correo_visi', 100); // Columna de texto con el correo del visitante (máx. 100)
            $table->primary('Docu_visi'); // Define el documento del visitante como clave primaria
        });
    }

    // Método que revierte la migración (elimina la tabla)
    public function down(): void
    {
        Schema::dropIfExists('visitantes'); // Elimina la tabla 'visitantes'
    }
};
