<?php

namespace Database\Seeders;

use App\Models\Dossier;
use App\Models\Formateur;
use App\Models\Formation;
use App\Models\Participant;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        $user = User::firstOrCreate(
            ['email' => 'test@example.com'],
            ['name' => 'Test User', 'password' => bcrypt('password')],
        );

        $formateurs = Formateur::factory(10)->create(['cree_par' => $user->id]);

        Formation::factory(20)->create([
            'id_formateur' => fn () => $formateurs->random()->id,
            'cree_par' => $user->id,
        ]);

        $participants = Participant::factory(30)->create(['cree_par' => $user->id]);

        Dossier::factory(50)->create([
            'participant_id' => fn () => $participants->random()->id,
            'cree_par' => $user->id,
        ]);
    }
}
