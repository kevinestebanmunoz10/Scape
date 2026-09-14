<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('parentesco', function (Blueprint $table) {
            $table->increments('id_parentesco');
            $table->text('parentesco')->nullable();
            $table->unsignedInteger('Estado_paren')->nullable();
            $table->foreign('Estado_paren')->references('id_estado')->on('estado');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('parentesco');
    }
};
