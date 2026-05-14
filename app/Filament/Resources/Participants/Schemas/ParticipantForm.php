<?php

namespace App\Filament\Resources\Participants\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class ParticipantForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('photo'),
                TextInput::make('nom')
                    ->required(),
                TextInput::make('cin')
                    ->required(),
                TextInput::make('telephone')
                    ->tel()
                    ->required(),
                TextInput::make('num_permis')
                    ->required(),
                Select::make('categorie_formation')
                    ->options(['TRM' => 'T r m', 'TRV' => 'T r v'])
                    ->required(),
                TextInput::make('cree_par')
                    ->required()
                    ->numeric(),
            ]);
    }
}
