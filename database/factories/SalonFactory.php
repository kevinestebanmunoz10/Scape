<?php

// Apertura del script PHP

// Espacio de nombres de las fábricas de datos

namespace Database\Factories;

// Importamos la clase base de las fábricas de Eloquent
use App\Models\Salon;
// Fachada para escribir directamente en la tabla de estados
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\DB;

/**
 * @extends Factory<Salon>
 */
// Fábrica encargada de generar datos de prueba para el modelo Salon
class SalonFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    // Método que define el estado por defecto de los datos generados
    public function definition(): array
    {
        // El salón hereda el estado activo que ya existe en la tabla estado
        $estadoActivo = DB::table('estado')->where('estado', 1)->value('id_estado') ?? 1;

        // Devuelve el arreglo de atributos por defecto del salón
        return [
            'codigo' => fake()->unique()->numerify('###'), // Código único de 3 dígitos generado al azar
            'capacidad' => fake()->numberBetween(20, 40), // Capacidad aleatoria entre 20 y 40 estudiantes
            'id_Estado' => $estadoActivo, // Estado activo del salón
        ];
    }
}
