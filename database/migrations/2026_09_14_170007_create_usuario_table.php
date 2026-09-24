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
        // Crea la tabla 'usuario'
        Schema::create('usuario', function (Blueprint $table) {
            $table->bigInteger('Documento'); // Columna entera grande con el número de documento como identificador
            $table->string('Nom_usua', 100); // Columna de texto con el nombre del usuario (máx. 100)
            $table->string('email', 100)->unique(); // Columna de correo electrónico única (máx. 100)
            $table->string('Telefono', 20); // Columna de texto con el teléfono del usuario (máx. 20)
            $table->string('QR', 255); // Columna de texto con el código QR asignado (máx. 255)
            $table->string('Contrasena', 255); // Columna de texto con la contraseña (hasheada) (máx. 255)
            $table->unsignedInteger('id_rol'); // ID del rol asignado al usuario
            $table->unsignedInteger('id_Estado'); // ID del estado del usuario
            $table->integer('cod_postal'); // Código postal de la ciudad del usuario
            $table->string('NIT', 20)->nullable(); // NIT (nullable) para usuarios tipo instituciones/empresas
            $table->primary('Documento'); // Define el documento como clave primaria
            $table->foreign('id_rol')->references('id_rol')->on('rol'); // Llave foránea hacia la tabla 'rol'
            $table->foreign('id_Estado')->references('id_estado')->on('estado'); // Llave foránea hacia la tabla 'estado'
            $table->foreign('cod_postal')->references('cod_Postal')->on('ciudad'); // Llave foránea hacia la tabla 'ciudad'
        });
    }

    // Método que revierte la migración (elimina la tabla)
    public function down(): void
    {
        Schema::dropIfExists('usuario'); // Elimina la tabla 'usuario'
    }
};
