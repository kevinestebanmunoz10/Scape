<?php

// Apertura del script PHP

// Clase base para las migraciones de Laravel
use Illuminate\Database\Migrations\Migration;
// Blueprint permite definir la estructura de las tablas
use Illuminate\Database\Schema\Blueprint;
// Fachada de base de datos para operaciones directas (insertar/actualizar)
use Illuminate\Support\Facades\DB;
// Fachada de esquema para crear y eliminar tablas
use Illuminate\Support\Facades\Schema;

// Define la clase anónima de la migración que Laravel ejecutará
return new class extends Migration
{
    // Método que ejecuta la migración (crea la tabla e inserta datos iniciales)
    public function up(): void
    {
        // Crea la tabla 'estado'
        Schema::create('estado', function (Blueprint $table) {
            $table->increments('id_estado'); // Columna auto-incremental con el identificador del estado
            $table->tinyInteger('estado'); // Columna entera pequeña que guarda el valor del estado
        });

        // Inserta o actualiza un registro para representar el estado "eliminado" o inactivo
        DB::table('estado')->updateOrInsert(
            ['id_estado' => 2], // Criterio de búsqueda: id_estado igual a 2
            ['estado' => 0], // Valor asignado: estado igual a 0
        );
    }

    // Método que revierte la migración (elimina la tabla)
    public function down(): void
    {
        Schema::dropIfExists('estado'); // Elimina la tabla 'estado'
    }
};
