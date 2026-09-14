<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('prestamo_equipo', function (Blueprint $table) {
            $table->increments('id_Prestamo_equi');
            $table->dateTime('f_Prestamo');
            $table->dateTime('f_devolucion')->nullable();
            $table->string('motivo', 255)->nullable();
            $table->bigInteger('Documento_pres');
            $table->string('serial_equi', 50);
            $table->unsignedInteger('id_estado');
            $table->foreign('Documento_pres')->references('Documento')->on('usuario');
            $table->foreign('serial_equi')->references('serial_equi')->on('equipo');
            $table->foreign('id_estado')->references('id_estado')->on('estado');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('prestamo_equipo');
    }
};
