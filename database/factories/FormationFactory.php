<?php

namespace Database\Factories;

use App\Models\Formateur;
use App\Models\Formation;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Formation>
 */
class FormationFactory extends Factory
{
    public function definition(): array
    {
        $typeFormation = fake()->randomElement(['FQIMO', 'FCO']);
        $categorieFormation = fake()->randomElement(['TRM', 'TRV']);
        $dateDepart = fake()->dateTimeBetween('now', '+3 months');
        $dateFin = fake()->dateTimeBetween($dateDepart, '+6 months');

        return [
            'titre' => "Formation {$typeFormation} {$categorieFormation} - ".fake()->city(),
            'type_formation' => $typeFormation,
            'categorie_formation' => $categorieFormation,
            'id_formateur' => Formateur::factory(),
            'prix' => fake()->randomElement([1500, 2000, 2500, 3000, 3500]),
            'date_depart' => $dateDepart,
            'date_fin' => $dateFin,
            'cree_par' => User::factory(),
        ];
    }
}
