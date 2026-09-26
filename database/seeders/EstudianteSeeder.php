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

// Seeder encargado de crear el rol y los usuarios estudiantes de ejemplo
class EstudianteSeeder extends Seeder
{
    // Desactiva los eventos de modelos al ejecutar los seeders
    use WithoutModelEvents;

    /**
     * Run the database seeds.
     */
    // Método que ejecuta el llenado de datos de los estudiantes
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

        // Inserta o actualiza el rol "Estudiante"
        DB::table('rol')->updateOrInsert(
            ['id_rol' => 5], // Criterio de búsqueda: id_rol igual a 5
            ['rol' => 'Estudiante'], // Valor asignado: nombre del rol
        );

        // Estudiantes de ejemplo que se crearán en la tabla de usuarios
        $estudiantes = [
            ['Documento' => 20000000010, 'Nom_usua' => 'Mateo Herrera Salazar', 'Telefono' => '3105550001', 'email' => 'estudiante1@scape.edu.co'],
            ['Documento' => 20000000011, 'Nom_usua' => 'Valentina Cruz Ospina', 'Telefono' => '3105550002', 'email' => 'estudiante2@scape.edu.co'],
            ['Documento' => 20000000012, 'Nom_usua' => 'Sebastián Ramírez Londoño', 'Telefono' => '3105550003', 'email' => 'estudiante3@scape.edu.co'],
            ['Documento' => 20000000013, 'Nom_usua' => 'Isabella Nicole Duarte Peña', 'Telefono' => '3105550004', 'email' => 'estudiante4@scape.edu.co'],
            ['Documento' => 20000000014, 'Nom_usua' => 'Daniel Felipe Vargas Mina', 'Telefono' => '3105550005', 'email' => 'estudiante5@scape.edu.co'],
        ];

        // Crea o actualiza cada usuario estudiante con sus datos asociados
        foreach ($estudiantes as $estudiante) {
            User::updateOrCreate(
                ['Documento' => $estudiante['Documento']], // Criterio de búsqueda: documento identificador del estudiante
                [
                    'Nom_usua' => $estudiante['Nom_usua'], // Nombre del estudiante
                    'email' => $estudiante['email'], // Correo electrónico del estudiante
                    'Telefono' => $estudiante['Telefono'], // Número de teléfono del estudiante
                    'QR' => 'QR-'.$estudiante['Documento'], // Código QR asignado
                    'Contrasena' => 'estudiante123', // Contraseña del usuario (por defecto en texto plano)
                    'id_rol' => 5, // Rol estudiante
                    'id_Estado' => 1, // Estado activo
                    'cod_postal' => 730001, // Código postal de la ciudad (Ibagué)
                ],
            );
        }
    }
}
