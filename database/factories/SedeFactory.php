<?php

// Apertura del script PHP

// Espacio de nombres de las fábricas de datos

namespace Database\Factories;

// Importamos la clase base de las fábricas de Eloquent
use App\Models\Sede;
// Clase base de las fábricas
use Illuminate\Database\Eloquent\Factories\Factory;
// Fachada para escribir directamente en la tabla de estados
use Illuminate\Support\Facades\DB;

/**
 * @extends Factory<Sede>
 */
// Fábrica encargada de generar datos de prueba para el modelo Sede
class SedeFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    // Método que define el estado por defecto de los datos generados
    public function definition(): array
    {
        // La sede hereda el estado activo que ya existe en la tabla estado
        $estadoActivo = DB::table('estado')->where('estado', 1)->value('id_estado') ?? 1;

        // Devuelve el arreglo de atributos por defecto de la sede
        return [
            'Nit' => fake()->unique()->numerify('#########'), // NIT único de 9 dígitos generado al azar
            'nombre' => 'Sede '.fake()->unique()->city(), // Nombre aleatorio para la sede
            'cod_postal' => 730001, // Código postal de la ciudad donde se ubica la sede
            'id_Estado' => $estadoActivo, // Estado activo de la sede
        ];
    }

    // Crea un modelo ya persistido junto con la ciudad que exige el código postal
    public function configure(): static
    {
        // Asegura la existencia de la ciudad antes de insertar la sede
        return $this->afterMaking(function (Sede $sede): void {
            // Inserta el departamento de referencia solo si todavía no está en la base de datos
            DB::table('departamento')->updateOrInsert(
                ['Codi_Departa' => 73], // Criterio de búsqueda: código del departamento
                ['Departamento' => 'Tolima'], // Valor asignado: nombre del departamento
            );

            // Inserta la ciudad de referencia solo si todavía no está en la base de datos
            DB::table('ciudad')->updateOrInsert(
                ['cod_Postal' => $sede->cod_postal], // Criterio de búsqueda: código postal de la ciudad
                ['Ciudad' => 'Ciudad '.$sede->cod_postal, 'Codi_Departa' => 73], // Valores asignados: nombre de la ciudad y departamento
            );
        });
    }
}
