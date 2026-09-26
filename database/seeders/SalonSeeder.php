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

// Seeder encargado de llenar la tabla de salones disponibles
class SalonSeeder extends Seeder
{
    // Desactiva los eventos de modelos al ejecutar los seeders
    use WithoutModelEvents;

    /**
     * Run the database seeds.
     */
    // Método que ejecuta el llenado de datos de los salones
    public function run(): void
    {
        // Inserta o actualiza el estado activo (1)
        DB::table('estado')->updateOrInsert(
            ['id_estado' => 1], // Criterio de búsqueda: id_estado igual a 1
            ['estado' => 1], // Valor asignado: estado activo
        );

        // Estado activo que se asigna a los salones creados
        $estadoActivo = DB::table('estado')->where('estado', 1)->value('id_estado') ?? 1;

        // Salones iniciales con su código y la capacidad de estudiantes que admiten
        $salones = [
            ['codigo' => '101', 'capacidad' => 35],
            ['codigo' => '102', 'capacidad' => 35],
            ['codigo' => '201', 'capacidad' => 40],
            ['codigo' => '202', 'capacidad' => 40],
            ['codigo' => '301', 'capacidad' => 30],
        ];

        // Recorre cada salón y lo inserta si no existe
        foreach ($salones as $salon) {
            DB::table('salones')->updateOrInsert(
                ['codigo' => $salon['codigo']], // Criterio de búsqueda: código del salón
                ['capacidad' => $salon['capacidad'], 'id_Estado' => $estadoActivo], // Valores asignados: capacidad y estado activo
            );
        }
    }
}
