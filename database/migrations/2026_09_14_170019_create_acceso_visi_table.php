<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('acceso_visi', function (Blueprint $table) {
            $table->increments('id_acces_visi');
            $table->dateTime('f_ingreso');
            $table->dateTime('f_salida')->nullable();
            $table->string('Documento_visi', 20)->nullable();
            $table->unsignedInteger('id_Estado_visi')->nullable();
            $table->string('Lugar', 150)->nullable();
            $table->foreign('Documento_visi')->references('Docu_visi')->on('visitantes');
            $table->foreign('id_Estado_visi')->references('id_estado')->on('estado');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('acceso_visi');
    }
};
