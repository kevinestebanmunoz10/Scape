<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('acceso_equi', function (Blueprint $table) {
            $table->increments('id_regi_equip');
            $table->dateTime('f_entrada');
            $table->dateTime('f_salida')->nullable();
            $table->string('serial_equi', 50);
            $table->bigInteger('Documento');
            $table->foreign('serial_equi')->references('serial_equi')->on('equipo');
            $table->foreign('Documento')->references('Documento')->on('usuario');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('acceso_equi');
    }
};
