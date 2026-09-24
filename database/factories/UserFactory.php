<?php

// Apertura del script PHP

// Espacio de nombres de las fábricas de datos

namespace Database\Factories;

// Modelo de usuario de la aplicación
use App\Models\User;
// Clase base de las fábricas de Eloquent
use Illuminate\Database\Eloquent\Factories\Factory;
// Fachada para generar los hashes de contraseñas
use Illuminate\Support\Facades\Hash;

/**
 * @extends Factory<User>
 */
// Fábrica encargada de generar datos de prueba para el modelo User
class UserFactory extends Factory
{
    /**
     * The current password being used by the factory.
     */
    // Propiedad estática que guarda la contraseña actual generada por la fábrica
    protected static ?string $password;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    // Método que define el estado por defecto de los datos generados
    public function definition(): array
    {
        // Devuelve el arreglo de atributos por defecto del usuario
        return [
            'Documento' => fake()->unique()->numerify('##########'), // Documento único de 10 dígitos generado al azar
            'Nom_usua' => fake()->name(), // Nombre de usuario aleatorio
            'email' => fake()->unique()->safeEmail(), // Correo electrónico único y seguro
            'Telefono' => fake()->numerify('3########'), // Teléfono colombiano aleatorio que comienza con 3
            'QR' => fake()->unique()->regexify('QR-[0-9]{3}'), // Código QR único con el formato QR-XXX
            'Contrasena' => static::$password ??= Hash::make('password'), // Contraseña hasheada (reutiliza la misma en toda la fábrica)
            'id_rol' => 5, // Rol asignado por defecto
            'id_Estado' => 1, // Estado activo por defecto
            'cod_postal' => 730001, // Código postal de la ciudad (Ibagué)
        ];
    }
}
