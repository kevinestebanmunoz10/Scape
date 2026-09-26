<?php

// Apertura del script PHP

// Espacio de nombres de las fábricas de datos

namespace Database\Factories;

// Modelo de usuario de la aplicación
use App\Models\Matricula;
// Clase base de las fábricas de Eloquent
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Matricula>
 */
// Fábrica encargada de generar datos de prueba para el modelo Matricula
class MatriculaFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    // Método que define el estado por defecto de los datos generados
    public function definition(): array
    {
        // Devuelve el arreglo de atributos por defecto de la matrícula
        return [
            'grado' => (string) fake()->numberBetween(1, 11), // Grado aleatorio entre 1 y 11
            'fecha_matri' => fake()->dateTimeThisYear(), // Fecha de matrícula aleatoria dentro del año actual
            'Documento_matri' => User::factory(), // Estudiante al que pertenece la matrícula
            'Jornada' => fake()->randomElement(['Mañana', 'Tarde']), // Jornada escolar aleatoria
            'Salon' => (string) fake()->unique()->numerify('###'), // Código de salón aleatorio de 3 dígitos
            'Nit' => null, // La sede es opcional en la matrícula
        ];
    }
}
