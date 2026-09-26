<?php

// Apertura del script PHP

// Espacio de nombres de las fábricas de datos

namespace Database\Factories;

// Modelo de jornada de la aplicación
use App\Models\Jornada;
// Clase base de las fábricas de Eloquent
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Jornada>
 */
// Fábrica encargada de generar datos de prueba para el modelo Jornada
class JornadaFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    // Método que define el estado por defecto de los datos generados
    public function definition(): array
    {
        // Devuelve el arreglo de atributos por defecto de la jornada
        return [
            'jornada' => fake()->unique()->randomElement(['Mañana', 'Tarde', 'Noche']), // Jornada aleatoria del catálogo
        ];
    }
}
