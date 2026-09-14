<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('acceso_usu', function (Blueprint $table) {
            $table->increments('id_acces_usu');
            $table->dateTime('f_ingreso');
            $table->dateTime('f_salida')->nullable();
            $table->bigInteger('Documento_acces')->nullable();
            $table->unsignedInteger('id_Estado_usu')->nullable();
            $table->foreign('Documento_acces')->references('Documento')->on('usuario');
            $table->foreign('id_Estado_usu')->references('id_estado')->on('estado');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('acceso_usu');
    }
};
