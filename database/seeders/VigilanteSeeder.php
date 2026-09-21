<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class VigilanteSeeder extends Seeder
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
            ['id_rol' => 4],
            ['rol' => 'Vigilante'],
        );

        User::updateOrCreate(
            ['Documento' => 10000000004],
            [
                'Nom_usua' => 'Vigilante SCAPE',
                'email' => 'vigilante@scape.edu.co',
                'Telefono' => '3000000003',
                'QR' => 'QR-VIGILANTE',
                'Contrasena' => 'vigilante123',
                'id_rol' => 4,
                'id_Estado' => 1,
                'cod_postal' => 730001,
            ],
        );
    }
}
