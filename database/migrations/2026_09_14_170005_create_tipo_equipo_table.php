<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tipo_equipo', function (Blueprint $table) {
            $table->increments('id_t_equip');
            $table->string('tipo', 50);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tipo_equipo');
    }
};
