<?php

// Apertura del script PHP

// Espacio de nombres de las fábricas de datos

namespace Database\Factories;

// Importamos la clase base de las fábricas de Eloquent
use App\Models\Parentesco;
// Clase base de las fábricas
use Illuminate\Database\Eloquent\Factories\Factory;
// Fachada para escribir directamente en la tabla de estados
use Illuminate\Support\Facades\DB;

/**
 * @extends Factory<Parentesco>
 */
// Fábrica encargada de generar datos de prueba para el modelo Parentesco
class ParentescoFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    // Método que define el estado por defecto de los datos generados
    public function definition(): array
    {
        // El parentesco hereda el estado activo que ya existe en la tabla estado
        $estadoActivo = DB::table('estado')->where('estado', 1)->value('id_estado') ?? 1;

        // Devuelve el arreglo de atributos por defecto del parentesco
        return [
            'parentesco' => fake()->unique()->randomElement(['Padre', 'Madre', 'Abuelo', 'Abuela', 'Hermano', 'Tía', 'Tío', 'Primo']), // Parentesco aleatorio
            'Estado_paren' => $estadoActivo, // Estado activo del parentesco
        ];
    }
}
