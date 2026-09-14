<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('acceso_estu', function (Blueprint $table) {
            $table->increments('id_acces_estu');
            $table->dateTime('f_ingreso');
            $table->dateTime('f_salida')->nullable();
            $table->bigInteger('Documento_estu')->nullable();
            $table->unsignedInteger('id_autorizacion')->nullable();
            $table->foreign('Documento_estu')->references('Documento')->on('usuario');
            $table->foreign('id_autorizacion')->references('id_autorizacion')->on('autorizacion');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('acceso_estu');
    }
};
