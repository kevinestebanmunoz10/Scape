<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;

/**
 * @extends Factory<User>
 */
class UserFactory extends Factory
{
    /**
     * The current password being used by the factory.
     */
    protected static ?string $password;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'Documento' => fake()->unique()->numerify('##########'),
            'Nom_usua' => fake()->name(),
            'email' => fake()->unique()->safeEmail(),
            'Telefono' => fake()->numerify('3########'),
            'QR' => fake()->unique()->regexify('QR-[0-9]{3}'),
            'Contrasena' => static::$password ??= Hash::make('password'),
            'id_rol' => 5,
            'id_Estado' => 1,
            'cod_postal' => 730001,
        ];
    }
}
