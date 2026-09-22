<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RectorSeeder extends Seeder
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
            ['id_rol' => 3],
            ['rol' => 'Rector'],
        );

        User::updateOrCreate(
            ['Documento' => 10000000003],
            [
                'Nom_usua' => 'Rector SCAPE',
                'email' => 'rector@scape.edu.co',
                'Telefono' => '3000000002',
                'QR' => 'QR-RECTOR',
                'Contrasena' => 'rector123',
                'id_rol' => 3,
                'id_Estado' => 1,
                'cod_postal' => 730001,
            ],
        );
    }
}
