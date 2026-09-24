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

// Seeder encargado de crear los tipos de permiso de salida por defecto
class TipoPermisoSeeder extends Seeder
{
    // Desactiva los eventos de modelos al ejecutar los seeders
    use WithoutModelEvents;

    /**
     * Run the database seeds.
     */
    // Método que ejecuta el llenado de datos de los tipos de permiso
    public function run(): void
    {
        // Tipos de permiso iniciales de la aplicación
        $tipos = ['Médico', 'Calamidad', 'Cita médica', 'Trámite personal', 'Otro'];

        // Recorre cada tipo de permiso y lo inserta si no existe
        foreach ($tipos as $tipo) {
            DB::table('tipo_permiso')->updateOrInsert(
                ['tipo' => $tipo], // Criterio de búsqueda: nombre del tipo de permiso
                ['tipo' => $tipo], // Valor asignado: nombre del tipo de permiso
            );
        }
    }
}
