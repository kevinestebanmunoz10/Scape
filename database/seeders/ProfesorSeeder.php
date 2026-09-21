<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProfesorSeeder extends Seeder
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

        DB::table('rol')->updateOrInsert(
            ['id_rol' => 2],
            ['rol' => 'Profesor'],
        );

        User::updateOrCreate(
            ['Documento' => 10000000002],
            [
                'Nom_usua' => 'Profesor SCAPE',
                'email' => 'profesor@scape.edu.co',
                'Telefono' => '3000000001',
                'QR' => 'QR-PROFESOR',
                'Contrasena' => 'profesor123',
                'id_rol' => 2,
                'id_Estado' => 1,
                'cod_postal' => 730001,
            ],
        );
    }
}
