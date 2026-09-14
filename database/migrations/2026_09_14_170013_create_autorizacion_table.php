<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('autorizacion', function (Blueprint $table) {
            $table->increments('id_autorizacion');
            $table->string('Permiso', 100);
            $table->date('Fecha')->nullable();
            $table->unsignedInteger('id_rol');
            $table->unsignedInteger('id_estu_acud')->nullable();
            $table->bigInteger('Documento_auto');
            $table->foreign('id_rol')->references('id_rol')->on('rol');
            $table->foreign('id_estu_acud')->references('id_estu_acud')->on('estudiante_acudiente');
            $table->foreign('Documento_auto')->references('Documento')->on('usuario');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('autorizacion');
    }
};
