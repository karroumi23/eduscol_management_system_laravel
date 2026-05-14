<?php

namespace App\Filament\Resources\Dossiers\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class DossierForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('participant_id')
                    ->required()
                    ->numeric(),
                TextInput::make('photo'),
                TextInput::make('nom')
                    ->required(),
                TextInput::make('cin')
                    ->required(),
                TextInput::make('telephone')
                    ->tel()
                    ->required(),
                DatePicker::make('date_naissance')
                    ->required(),
                TextInput::make('permis')
                    ->required(),
                Select::make('categorie_formation')
                    ->options(['TRM' => 'T r m', 'TRV' => 'T r v'])
                    ->required(),
                Select::make('type_formation')
                    ->options(['FQIMO' => 'F q i m o', 'FCO' => 'F c o'])
                    ->required(),
                TextInput::make('nom_formateur')
                    ->required(),
                TextInput::make('prix_formation')
                    ->required()
                    ->numeric(),
                TextInput::make('acompte')
                    ->required()
                    ->numeric()
                    ->default(0.0),
                DatePicker::make('date_depart_formation')
                    ->required(),
                DatePicker::make('date_fin_formation')
                    ->required(),
                TextInput::make('cree_par')
                    ->required()
                    ->numeric(),
            ]);
    }
}
