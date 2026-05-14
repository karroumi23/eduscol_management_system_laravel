<?php

namespace Database\Factories;

use App\Models\Participant;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Participant>
 */
class ParticipantFactory extends Factory
{
    public function definition(): array
    {
        return [
            'nom' => fake()->name(),
            'cin' => strtoupper(fake()->unique()->bothify('??######')),
            'telephone' => fake()->numerify('06########'),
            'num_permis' => strtoupper(fake()->unique()->bothify('??#######')),
            'categorie_formation' => fake()->randomElement(['TRM', 'TRV']),
            'cree_par' => User::factory(),
        ];
    }
}
