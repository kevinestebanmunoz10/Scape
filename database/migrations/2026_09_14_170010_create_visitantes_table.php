<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('visitantes', function (Blueprint $table) {
            $table->string('Docu_visi', 20);
            $table->string('Nom_visi', 100);
            $table->string('tel_visi', 20);
            $table->string('correo_visi', 100);
            $table->primary('Docu_visi');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('visitantes');
    }
};
