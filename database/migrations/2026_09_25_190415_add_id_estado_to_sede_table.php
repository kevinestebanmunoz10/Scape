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
    // Método que ejecuta la migración (agrega la columna de estado a la sede)
    public function up(): void
    {
        // Modifica la tabla 'sede' existente para agregar el estado
        Schema::table('sede', function (Blueprint $table) {
            $table->unsignedInteger('id_Estado')->default(1)->after('cod_postal'); // ID del estado de la sede (1 = activa por defecto)
            $table->foreign('id_Estado')->references('id_estado')->on('estado'); // Llave foránea hacia la tabla 'estado'
        });
    }

    // Método que revierte la migración (elimina la columna de estado)
    public function down(): void
    {
        // Elimina la llave foránea antes de borrar la columna que la sostiene
        Schema::table('sede', function (Blueprint $table) {
            $table->dropForeign(['id_Estado']); // Elimina la llave foránea hacia 'estado'

            $table->dropColumn('id_Estado'); // Elimina la columna de estado de la sede
        });
    }
};
