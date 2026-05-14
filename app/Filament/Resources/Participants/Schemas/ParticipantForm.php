<?php

namespace App\Filament\Resources\Participants\Schemas;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class ParticipantForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Photo de profil')
                    ->description('Photo d\'identité du participant.')
                    ->icon('heroicon-o-camera')
                    ->schema([
                        FileUpload::make('photo')
                            ->label('')
                            ->image()
                            ->imageEditor()
                            ->directory('participants/photos')
                            ->avatar()
                            ->columnSpanFull(),
                    ])
                    ->aside()
                    ->columnSpanFull(),

                Section::make('Informations personnelles')
                    ->description('Données d\'identité du participant.')
                    ->icon('heroicon-o-user')
                    ->schema([
                        TextInput::make('nom')
                            ->label('Nom complet')
                            ->required()
                            ->maxLength(255),
                        TextInput::make('cin')
                            ->label('CIN')
                            ->required()
                            ->unique(ignoreRecord: true)
                            ->maxLength(255),
                        TextInput::make('telephone')
                            ->label('Téléphone')
                            ->tel()
                            ->required()
                            ->maxLength(255),
                        TextInput::make('num_permis')
                            ->label('Numéro de permis')
                            ->required()
                            ->unique(ignoreRecord: true)
                            ->maxLength(255),
                        Select::make('categorie_formation')
                            ->label('Catégorie de formation')
                            ->options([
                                'TRM' => 'TRM',
                                'TRV' => 'TRV',
                            ])
                            ->required()
                            ->native(false),
                    ])
                    ->columns(2)
                    ->columnSpanFull(),
            ]);
    }
}
