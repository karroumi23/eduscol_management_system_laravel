<?php

namespace App\Filament\Resources\Formateurs\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class FormateurForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Identité')
                    ->description('Nom et prénom du formateur.')
                    ->icon('heroicon-o-identification')
                    ->schema([
                        TextInput::make('prenom')
                            ->label('Prénom')
                            ->required()
                            ->maxLength(255),
                        TextInput::make('nom')
                            ->label('Nom')
                            ->required()
                            ->maxLength(255),
                    ])
                    ->columns(2),

                Section::make('Coordonnées')
                    ->description('Informations de contact du formateur.')
                    ->icon('heroicon-o-phone')
                    ->schema([
                        TextInput::make('telephone')
                            ->label('Téléphone')
                            ->tel()
                            ->required()
                            ->maxLength(255),
                        TextInput::make('email')
                            ->label('Email')
                            ->email()
                            ->maxLength(255),
                    ])
                    ->columns(2),
            ]);
    }
}
