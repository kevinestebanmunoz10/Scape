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

// Seeder encargado de llenar la tabla de sedes de la institución
class SedeSeeder extends Seeder
{
    // Desactiva los eventos de modelos al ejecutar los seeders
    use WithoutModelEvents;

    /**
     * Run the database seeds.
     */
    // Método que ejecuta el llenado de datos de las sedes
    public function run(): void
    {
        // La columna id_Estado de la sede referencia esta tabla, así que se garantizan sus filas
        DB::table('estado')->updateOrInsert(
            ['id_estado' => 1], // Criterio de búsqueda: estado activo
            ['estado' => 1], // Valor asignado: sede activa
        );

        DB::table('estado')->updateOrInsert(
            ['id_estado' => 2], // Criterio de búsqueda: estado inactivo
            ['estado' => 0], // Valor asignado: sede inactiva
        );

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

        // Sedes iniciales de la institución, todas ubicadas en Ibagué
        $sedes = [
            ['Nit' => 900100001, 'nombre' => 'Sede Principal - Campus Central', 'cod_postal' => 730001],
            ['Nit' => 900100002, 'nombre' => 'Sede Norte - Aulas Técnicas', 'cod_postal' => 730001],
            ['Nit' => 900100003, 'nombre' => 'Sede Sur - Instalaciones Deportivas', 'cod_postal' => 730001],
        ];

        // Recorre cada sede y la inserta si no existe
        foreach ($sedes as $sede) {
            DB::table('sede')->updateOrInsert(
                ['Nit' => $sede['Nit']], // Criterio de búsqueda: NIT de la sede
                [
                    'nombre' => $sede['nombre'], // Valor asignado: nombre de la sede
                    'cod_postal' => $sede['cod_postal'], // Valor asignado: código postal de la ciudad
                ], // 'id_Estado' se omite a propósito: no se sobrescribe para no reactivar sedes desactivadas
            );
        }
    }
}
