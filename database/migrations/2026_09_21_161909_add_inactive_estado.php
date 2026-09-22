<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        DB::table('estado')->updateOrInsert(
            ['id_estado' => 2],
            ['estado' => 0],
        );
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::table('usuario')->where('id_Estado', 2)->update(['id_Estado' => 1]);
        DB::table('estado')->where('id_estado', 2)->delete();
    }
};
