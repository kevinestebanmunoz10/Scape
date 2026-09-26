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

// Seeder encargado de llenar la tabla de parentescos disponibles
class ParentescoSeeder extends Seeder
{
    // Desactiva los eventos de modelos al ejecutar los seeders
    use WithoutModelEvents;

    /**
     * Run the database seeds.
     */
    // Método que ejecuta el llenado de datos de los parentescos
    public function run(): void
    {
        // Estado activo que se asigna a los parentescos creados
        $estadoActivo = DB::table('estado')->where('estado', 1)->value('id_estado') ?? 1;

        // Parentescos iniciales disponibles para vincular a los acudientes
        $parentescos = ['Padre', 'Madre', 'Abuelo', 'Hermano', 'Tío'];

        // Recorre cada parentesco y lo inserta si no existe
        foreach ($parentescos as $parentesco) {
            DB::table('parentesco')->updateOrInsert(
                ['parentesco' => $parentesco], // Criterio de búsqueda: nombre del parentesco
                ['Estado_paren' => $estadoActivo], // Valor asignado: estado activo
            );
        }
    }
}
