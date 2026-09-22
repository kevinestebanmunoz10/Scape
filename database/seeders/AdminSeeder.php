<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AdminSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('departamento')->updateOrInsert(
            ['Codi_Departa' => 73],
            ['Departamento' => 'Tolima'],
        );

        DB::table('ciudad')->updateOrInsert(
            ['cod_Postal' => 730001],
            ['Ciudad' => 'Ibagué', 'Codi_Departa' => 73],
        );

        DB::table('estado')->updateOrInsert(
            ['id_estado' => 1],
            ['estado' => 1],
        );

        DB::table('estado')->updateOrInsert(
            ['id_estado' => 2],
            ['estado' => 0],
        );

        DB::table('rol')->updateOrInsert(
            ['id_rol' => 1],
            ['rol' => 'Administrador'],
        );

        User::updateOrCreate(
            ['Documento' => 10000000001],
            [
                'Nom_usua' => 'Administrador SCAPE',
                'email' => 'kevinestebanmunoz10@gmail.com',
                'Telefono' => '3000000000',
                'QR' => 'QR-ADMIN',
                'Contrasena' => 'admin123',
                'id_rol' => 1,
                'id_Estado' => 1,
                'cod_postal' => 730001,
            ],
        );
    }
}
