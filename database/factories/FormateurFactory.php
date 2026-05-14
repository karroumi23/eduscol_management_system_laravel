<?php

namespace Database\Factories;

use App\Models\Formateur;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Formateur>
 */
class FormateurFactory extends Factory
{
    public function definition(): array
    {
        return [
            'nom' => fake()->lastName(),
            'prenom' => fake()->firstName(),
            'telephone' => fake()->numerify('06########'),
            'email' => fake()->unique()->safeEmail(),
            'cree_par' => User::factory(),
        ];
    }
}
