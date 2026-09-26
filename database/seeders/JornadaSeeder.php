<?php

// Apertura del script PHP

// Espacio de nombres de los seeders de la base de datos

namespace Database\Seeders;

// Rasgo que evita la creación de eventos de modelos durante el seed
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
// Clase base de los seeders de Laravel
use Illuminate\Database\Seeder;
// Fachada de base de datos para operaciones directas (insertar/actualizar)
use Illuminate\Support\Facades\DB;

// Seeder encargado de llenar el catálogo de jornadas escolares
class JornadaSeeder extends Seeder
{
    // Desactiva los eventos de modelos al ejecutar los seeders
    use WithoutModelEvents;

    /**
     * Run the database seeds.
     */
    // Método que ejecuta el llenado de datos de las jornadas
    public function run(): void
    {
        // Jornadas iniciales disponibles para las matrículas
        $jornadas = ['Mañana', 'Tarde', 'Noche'];

        // Recorre cada jornada y la inserta si no existe
        foreach ($jornadas as $jornada) {
            DB::table('jornada')->updateOrInsert(
                ['jornada' => $jornada], // Criterio de búsqueda: nombre de la jornada
                ['jornada' => $jornada], // Valor asignado: nombre de la jornada
            );
        }
    }
}
