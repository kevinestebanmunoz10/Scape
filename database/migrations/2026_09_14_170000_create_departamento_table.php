<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('departamento', function (Blueprint $table) {
            $table->integer('Codi_Departa');
            $table->string('Departamento', 100);
            $table->primary('Codi_Departa');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('departamento');
    }
};
