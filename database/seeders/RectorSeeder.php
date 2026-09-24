<?php

// Apertura del script PHP

// Espacio de nombres de los seeders de la base de datos

namespace Database\Seeders;

// Modelo de usuario de la aplicación
use App\Models\User;
// Rasgo que evita la creación de eventos de modelos durante el seed
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
// Clase base de los seeders de Laravel
use Illuminate\Database\Seeder;
// Fachada de base de datos para operaciones directas (insertar/actualizar)
use Illuminate\Support\Facades\DB;

// Seeder encargado de crear el rol y el usuario rector
class RectorSeeder extends Seeder
{
    // Desactiva los eventos de modelos al ejecutar los seeders
    use WithoutModelEvents;

    /**
     * Run the database seeds.
     */
    // Método que ejecuta el llenado de datos del rector
    public function run(): void
    {
        // Inserta o actualiza el departamento "Tolima"
        DB::table('departamento')->updateOrInsert(
            ['Codi_Departa' => 73], // Criterio de búsqueda: código de departamento 73
            ['Departamento' => 'Tolima'], // Valor asignado: nombre del departamento
        );

        // Inserta o actualiza la ciudad "Ibagué" del departamento 73
        DB::table('ciudad')->updateOrInsert(
            ['cod_Postal' => 730001], // Criterio de búsqueda: código postal 730001
            ['Ciudad' => 'Ibagué', 'Codi_Departa' => 73], // Valores asignados: nombre de la ciudad y su departamento
        );

        // Inserta o actualiza el estado activo (1)
        DB::table('estado')->updateOrInsert(
            ['id_estado' => 1], // Criterio de búsqueda: id_estado igual a 1
            ['estado' => 1], // Valor asignado: estado activo
        );

        // Inserta o actualiza el estado inactivo (0)
        DB::table('estado')->updateOrInsert(
            ['id_estado' => 2], // Criterio de búsqueda: id_estado igual a 2
            ['estado' => 0], // Valor asignado: estado inactivo
        );

        // Inserta o actualiza el rol "Rector"
        DB::table('rol')->updateOrInsert(
            ['id_rol' => 3], // Criterio de búsqueda: id_rol igual a 3
            ['rol' => 'Rector'], // Valor asignado: nombre del rol
        );

        // Crea o actualiza el usuario rector con sus datos asociados
        User::updateOrCreate(
            ['Documento' => 10000000003], // Criterio de búsqueda: documento identificador del rector
            [
                'Nom_usua' => 'Rector SCAPE', // Nombre del usuario
                'email' => 'rector@scape.edu.co', // Correo electrónico del usuario
                'Telefono' => '3000000002', // Número de teléfono del usuario
                'QR' => 'QR-RECTOR', // Código QR asignado
                'Contrasena' => 'rector123', // Contraseña del usuario (por defecto en texto plano)
                'id_rol' => 3, // Rol rector
                'id_Estado' => 1, // Estado activo
                'cod_postal' => 730001, // Código postal de la ciudad (Ibagué)
            ],
        );
    }
}
