<?php

// Apertura del script PHP

// Espacio de nombres de los seeders de la base de datos

namespace Database\Seeders;

// Rasgo que evita la creación de eventos de modelos durante el seed
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
// Clase base de los seeders de Laravel
use Illuminate\Database\Seeder;

// Clase principal de semillas que invoca los demás seeders
class DatabaseSeeder extends Seeder
{
    // Desactiva los eventos de modelos al ejecutar los seeders
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    // Método que ejecuta el llenado inicial de la base de datos
    public function run(): void
    {
        // Ejecuta de forma secuencial los seeders de cada rol
        $this->call([
            AdminSeeder::class, // Llena los datos del administrador
            ProfesorSeeder::class, // Llena los datos del profesor
            RectorSeeder::class, // Llena los datos del rector
            VigilanteSeeder::class, // Llena los datos del vigilante
            TipoPermisoSeeder::class, // Llena los tipos de permiso de salida por defecto
        ]);
    }
}
