<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ciudad', function (Blueprint $table) {
            $table->integer('cod_Postal');
            $table->string('Ciudad', 100);
            $table->integer('Codi_Departa');
            $table->primary('cod_Postal');
            $table->foreign('Codi_Departa')->references('Codi_Departa')->on('departamento');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ciudad');
    }
};
