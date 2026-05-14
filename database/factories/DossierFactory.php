<?php

namespace Database\Factories;

use App\Models\Dossier;
use App\Models\Participant;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Dossier>
 */
class DossierFactory extends Factory
{
    public function definition(): array
    {
        $dateDepart = fake()->dateTimeBetween('-6 months', 'now');
        $dateFin = fake()->dateTimeBetween($dateDepart, '+3 months');
        $prix = fake()->randomFloat(2, 1000, 5000);

        return [
            'participant_id' => Participant::factory(),
            'nom' => fake()->name(),
            'cin' => strtoupper(fake()->bothify('??######')),
            'telephone' => fake()->numerify('06########'),
            'date_naissance' => fake()->dateTimeBetween('-60 years', '-18 years'),
            'permis' => strtoupper(fake()->bothify('??#######')),
            'categorie_formation' => fake()->randomElement(['TRM', 'TRV']),
            'type_formation' => fake()->randomElement(['FQIMO', 'FCO']),
            'nom_formateur' => fake()->name(),
            'prix_formation' => $prix,
            'acompte' => fake()->randomFloat(2, 0, $prix),
            'date_depart_formation' => $dateDepart,
            'date_fin_formation' => $dateFin,
            'cree_par' => User::factory(),
        ];
    }
}
