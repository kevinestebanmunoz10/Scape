<?php

// Apertura del script PHP

// Espacio de nombres de las fábricas de datos

namespace Database\Factories;

// Importamos la clase base de las fábricas de Eloquent
use App\Models\Acudiente;
// Clase base de las fábricas
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Acudiente>
 */
// Fábrica encargada de generar datos de prueba para el modelo Acudiente
class AcudienteFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    // Método que define el estado por defecto de los datos generados
    public function definition(): array
    {
        // Devuelve el arreglo de atributos por defecto del acudiente
        return [
            'Documento_acud' => (string) fake()->unique()->numerify('##########'), // Documento único de 10 dígitos generado al azar
            'nombre' => fake()->name(), // Nombre completo del acudiente
            'tel_acu' => fake()->numerify('3#########'), // Teléfono principal con formato colombiano
            'tel_acu2' => fake()->numerify('3#########'), // Teléfono alterno con formato colombiano
            'Direcccion_acu' => fake()->address(), // Dirección de residencia del acudiente
            'Correo_acud' => fake()->unique()->safeEmail(), // Correo electrónico único del acudiente
        ];
    }
}
