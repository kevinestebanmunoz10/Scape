<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('acudiente', function (Blueprint $table) {
            $table->string('Documento_acud', 20);
            $table->string('nombre', 100);
            $table->string('tel_acu', 20);
            $table->string('tel_acu2', 20)->nullable();
            $table->string('Direcccion_acu', 150)->nullable();
            $table->string('Correo_acud', 100);
            $table->primary('Documento_acud');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('acudiente');
    }
};
