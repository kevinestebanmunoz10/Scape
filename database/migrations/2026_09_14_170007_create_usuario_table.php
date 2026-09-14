<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('usuario', function (Blueprint $table) {
            $table->bigInteger('Documento');
            $table->string('Nom_usua', 100);
            $table->string('email', 100)->unique();
            $table->string('Telefono', 20);
            $table->string('QR', 255);
            $table->string('Contrasena', 255);
            $table->unsignedInteger('id_rol');
            $table->unsignedInteger('id_Estado');
            $table->integer('cod_postal');
            $table->primary('Documento');
            $table->foreign('id_rol')->references('id_rol')->on('rol');
            $table->foreign('id_Estado')->references('id_estado')->on('estado');
            $table->foreign('cod_postal')->references('cod_Postal')->on('ciudad');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('usuario');
    }
};
