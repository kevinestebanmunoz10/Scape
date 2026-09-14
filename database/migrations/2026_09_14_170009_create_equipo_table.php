<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('equipo', function (Blueprint $table) {
            $table->string('serial_equi', 50);
            $table->unsignedInteger('id_t_equip');
            $table->unsignedInteger('id_Marca');
            $table->string('Color', 30)->nullable();
            $table->string('imagen', 255)->nullable();
            $table->primary('serial_equi');
            $table->foreign('id_t_equip')->references('id_t_equip')->on('tipo_equipo');
            $table->foreign('id_Marca')->references('id_marca')->on('marca');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('equipo');
    }
};
