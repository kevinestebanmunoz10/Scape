<?php

// Apertura del script PHP

// Espacio de nombres de los seeders de la base de datos

namespace Database\Seeders;

// Rasgo que evita la creación de eventos de modelos durante el seed
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
// Clase base de los seeders de Laravel
use Illuminate\Database\Seeder;
// Fachada para encriptar las contraseñas de los usuarios de prueba
use Illuminate\Support\Facades\DB;
// Fachada de base de datos para operaciones directas (insertar/actualizar)
use Illuminate\Support\Facades\Hash;

// Seeder encargado de crear datos de prueba para el módulo de permisos de salida
class PermisosDataSeeder extends Seeder
{
    // Desactiva los eventos de modelos al ejecutar los seeders
    use WithoutModelEvents;

    /**
     * Run the database seeds.
     */
    // Método que ejecuta el llenado de los datos de prueba
    public function run(): void
    {
        // Estado activo que se asigna a los registros de prueba
        $estadoActivo = DB::table('estado')->value('id_estado') ?? 1;

        // Rol estudiante garantizado porque los estudiantes se identifican por su matrícula
        $rolEstudiante = DB::table('rol')->where('rol', 'Estudiante')->value('id_rol');
        if (! $rolEstudiante) {
            $rolEstudiante = DB::table('rol')->insertGetId(['rol' => 'Estudiante']);
        }

        // Parentescos garantizados para vincular a los acudientes
        $parentescos = ['Madre' => null, 'Padre' => null, 'Acudiente' => null];
        foreach ($parentescos as $nombre => $valor) {
            $parentescos[$nombre] = DB::table('parentesco')->where('parentesco', $nombre)->value('id_parentesco');
            if (! $parentescos[$nombre]) {
                $parentescos[$nombre] = DB::table('parentesco')->insertGetId([
                    'parentesco' => $nombre,
                    'Estado_paren' => $estadoActivo,
                ]);
            }
        }

        // Estudiantes de prueba que aparecerán en el selector de permisos
        $estudiantes = [
            ['Documento' => 20000000001, 'Nom_usua' => 'Ana María García Pérez', 'grado' => '11', 'Jornada' => 'Mañana', 'Salon' => '1101'],
            ['Documento' => 20000000002, 'Nom_usua' => 'Luis Fernando Pérez Gómez', 'grado' => '10', 'Jornada' => 'Mañana', 'Salon' => '1002'],
            ['Documento' => 20000000003, 'Nom_usua' => 'Carla Jiménez Ruiz', 'grado' => '9', 'Jornada' => 'Tarde', 'Salon' => '901'],
            ['Documento' => 20000000004, 'Nom_usua' => 'Diego Andrés Torres Sánchez', 'grado' => '11', 'Jornada' => 'Mañana', 'Salon' => '1102'],
        ];

        // Acudientes de prueba que aparecerán en el selector de permisos
        $acudientes = [
            ['Documento_acud' => '900000001', 'nombre' => 'María Pérez Restrepo', 'tel_acu' => '3101112233', 'tel_acu2' => '3112223344', 'Direcccion_acu' => 'Calle 1 # 10-20', 'Correo_acud' => 'maria.perez@correo.com'],
            ['Documento_acud' => '900000002', 'nombre' => 'Carlos Gómez Rodríguez', 'tel_acu' => '3103334455', 'tel_acu2' => '', 'Direcccion_acu' => 'Carrera 2 # 30-40', 'Correo_acud' => 'carlos.gomez@correo.com'],
            ['Documento_acud' => '900000003', 'nombre' => 'Laura Ruiz Castro', 'tel_acu' => '3105556677', 'tel_acu2' => '', 'Direcccion_acu' => 'Calle 5 # 12-34', 'Correo_acud' => 'laura.ruiz@correo.com'],
            ['Documento_acud' => '900000004', 'nombre' => 'Fernando Torres Molina', 'tel_acu' => '3107778899', 'tel_acu2' => '', 'Direcccion_acu' => 'Carrera 8 # 45-60', 'Correo_acud' => 'fernando.torres@correo.com'],
        ];

        // Relaciones estudiante-acudiente con su parentesco
        $vinculos = [
            ['Documento_estu' => 20000000001, 'Documento_acud' => '900000001', 'parentesco' => 'Madre'],
            ['Documento_estu' => 20000000002, 'Documento_acud' => '900000002', 'parentesco' => 'Padre'],
            ['Documento_estu' => 20000000003, 'Documento_acud' => '900000003', 'parentesco' => 'Madre'],
            ['Documento_estu' => 20000000004, 'Documento_acud' => '900000002', 'parentesco' => 'Padre'],
            ['Documento_estu' => 20000000004, 'Documento_acud' => '900000004', 'parentesco' => 'Padre'],
        ];

        // Registra los estudiantes de prueba (usuario y matrícula)
        foreach ($estudiantes as $estudiante) {
            DB::table('usuario')->updateOrInsert( // Registra o actualiza el usuario del estudiante
                ['Documento' => $estudiante['Documento']], // Llave natural del estudiante
                [ // Datos básicos del estudiante de prueba
                    'Nom_usua' => $estudiante['Nom_usua'], // Nombre completo del estudiante
                    'email' => 'estudiante'.$estudiante['Documento'].'@correo.com', // Correo único del estudiante
                    'Telefono' => '300'.substr((string) $estudiante['Documento'], -7), // Teléfono de contacto de prueba
                    'QR' => 'QR-'.$estudiante['Documento'], // Código QR de identificación
                    'Contrasena' => Hash::make('12345678'), // Contraseña de acceso de prueba
                    'id_rol' => $rolEstudiante, // Rol de estudiante
                    'id_Estado' => $estadoActivo, // Estado activo
                    'cod_postal' => 730001, // Código postal de la ciudad
                ],
            );

            DB::table('matricula')->updateOrInsert( // Registra o actualiza la matrícula del estudiante
                ['Documento_matri' => $estudiante['Documento']], // Llave natural de la matrícula
                [ // Datos académicos de la matrícula
                    'grado' => $estudiante['grado'], // Grado del estudiante
                    'fecha_matri' => now()->toDateString(), // Fecha actual de la matrícula
                    'Jornada' => $estudiante['Jornada'], // Jornada escolar
                    'Salon' => $estudiante['Salon'], // Salón asignado
                ],
            );
        }

        // Registra los acudientes de prueba
        foreach ($acudientes as $acudiente) {
            DB::table('acudiente')->updateOrInsert( // Registra o actualiza el acudiente
                ['Documento_acud' => $acudiente['Documento_acud']], // Llave natural del acudiente
                $acudiente, // Datos del acudiente
            );
        }

        // Vincula a cada estudiante con sus acudientes relacionados
        foreach ($vinculos as $vinculo) {
            DB::table('estudiante_acudiente')->updateOrInsert( // Registra o actualiza la relación
                [ // Llave natural de la relación
                    'Documento_estu' => $vinculo['Documento_estu'], // Documento del estudiante
                    'Documento_acud' => $vinculo['Documento_acud'], // Documento del acudiente
                ],
                ['id_parentesco' => $parentescos[$vinculo['parentesco']]], // Parentesco de la relación
            );
        }
    }
}
