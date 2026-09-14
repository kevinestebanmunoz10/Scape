<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('sede', function (Blueprint $table) {
            $table->integer('Nit');
            $table->string('nombre', 150);
            $table->integer('cod_postal');
            $table->primary('Nit');
            $table->foreign('cod_postal')->references('cod_Postal')->on('ciudad');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sede');
    }
};
