<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('matricula', function (Blueprint $table) {
            $table->increments('numero_matri');
            $table->string('grado', 20);
            $table->date('fecha_matri');
            $table->bigInteger('Documento_matri');
            $table->string('Jornada', 20)->nullable();
            $table->string('Salon', 50)->nullable();
            $table->integer('Nit')->nullable();
            $table->foreign('Documento_matri')->references('Documento')->on('usuario');
            $table->foreign('Nit')->references('Nit')->on('sede');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('matricula');
    }
};
