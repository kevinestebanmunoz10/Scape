<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('estudiante_acudiente', function (Blueprint $table) {
            $table->increments('id_estu_acud');
            $table->string('Documento_acud', 20);
            $table->bigInteger('Documento_estu');
            $table->unsignedInteger('id_parentesco')->nullable();
            $table->foreign('Documento_acud')->references('Documento_acud')->on('acudiente');
            $table->foreign('Documento_estu')->references('Documento')->on('usuario');
            $table->foreign('id_parentesco')->references('id_parentesco')->on('parentesco');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('estudiante_acudiente');
    }
};
