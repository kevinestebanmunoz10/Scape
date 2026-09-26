<?php

// Apertura del script PHP

// Espacio de nombres de los seeders de la base de datos

namespace Database\Seeders;

// Rasgo que evita la creación de eventos de modelos durante el seed
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
// Clase base de los seeders de Laravel
use Illuminate\Database\Seeder;
// Fachada para operaciones directas (insertar/actualizar)
use Illuminate\Support\Facades\DB;

// Seeder encargado de llenar el catálogo de acudientes reutilizables
class AcudienteSeeder extends Seeder
{
    // Desactiva los eventos de modelos al ejecutar los seeders
    use WithoutModelEvents;

    /**
     * Run the database seeds.
     */
    // Método que ejecuta el llenado de datos de los acudientes
    public function run(): void
    {
        // Acudientes iniciales, pensados para poder vincularse a varios estudiantes
        $acudientes = [
            [
                'Documento_acud' => '1002003004',
                'nombre' => 'Carmen Rosa Salazar',
                'tel_acu' => '3105554412',
                'tel_acu2' => '3125558890',
                'Direcccion_acu' => 'Calle 14 #6-25, Ibagué, Tolima',
                'Correo_acud' => 'carmen.salazar@correo.edu.co',
            ],
            [
                'Documento_acud' => '1002003005',
                'nombre' => 'Jorge Alberto Herrera',
                'tel_acu' => '3155557734',
                'tel_acu2' => '3165552281',
                'Direcccion_acu' => 'Carrera 7 #20-11, Ibagué, Tolima',
                'Correo_acud' => 'jorge.herrera@correo.edu.co',
            ],
            [
                'Documento_acud' => '1002003006',
                'nombre' => 'Luz Marina Ortega',
                'tel_acu' => '3205559016',
                'tel_acu2' => null,
                'Direcccion_acu' => 'Avenida 15 #3-40, Ibagué, Tolima',
                'Correo_acud' => 'luz.ortega@correo.edu.co',
            ],
        ];

        // Recorre cada acudiente y lo inserta o actualiza por su documento
        foreach ($acudientes as $acudiente) {
            DB::table('acudiente')->updateOrInsert(
                ['Documento_acud' => $acudiente['Documento_acud']], // Criterio de búsqueda: documento del acudiente
                $acudiente, // Valores asignados: datos de contacto del acudiente
            );
        }
    }
}
