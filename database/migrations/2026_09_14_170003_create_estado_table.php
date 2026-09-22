<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('estado', function (Blueprint $table) {
            $table->increments('id_estado');
            $table->tinyInteger('estado');
        });

        DB::table('estado')->updateOrInsert(
            ['id_estado' => 2],
            ['estado' => 0],
        );
    }

    public function down(): void
    {
        Schema::dropIfExists('estado');
    }
};
