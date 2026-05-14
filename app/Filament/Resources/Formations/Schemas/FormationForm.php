<?php

namespace App\Filament\Resources\Formations\Schemas;

use App\Models\Formateur;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class FormationForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Informations générales')
                    ->description('Titre et classification de la formation.')
                    ->icon('heroicon-o-academic-cap')
                    ->schema([
                        TextInput::make('titre')
                            ->label('Titre de la formation')
                            ->required()
                            ->maxLength(255)
                            ->columnSpanFull(),
                        Select::make('type_formation')
                            ->label('Type de formation')
                            ->options([
                                'FQIMO' => 'FQIMO',
                                'FCO' => 'FCO',
                            ])
                            ->required()
                            ->native(false),
                        Select::make('categorie_formation')
                            ->label('Catégorie')
                            ->options([
                                'TRM' => 'TRM',
                                'TRV' => 'TRV',
                            ])
                            ->required()
                            ->native(false),
                    ])
                    ->columns(2),

                Section::make('Formateur & Tarification')
                    ->description('Responsable de la formation et coût.')
                    ->icon('heroicon-o-user-circle')
                    ->schema([
                        Select::make('id_formateur')
                            ->label('Formateur')
                            ->options(
                                Formateur::query()
                                    ->get()
                                    ->mapWithKeys(fn (Formateur $f) => [$f->id => $f->nom_complet])
                            )
                            ->searchable()
                            ->required()
                            ->native(false)
                            ->columnSpanFull(),
                        TextInput::make('prix')
                            ->label('Prix (MAD)')
                            ->required()
                            ->numeric()
                            ->prefix('MAD')
                            ->columnSpanFull(),
                    ]),

                Section::make('Planning')
                    ->description('Dates de début et de fin de la formation.')
                    ->icon('heroicon-o-calendar')
                    ->schema([
                        DatePicker::make('date_depart')
                            ->label('Date de début')
                            ->required()
                            ->displayFormat('d/m/Y'),
                        DatePicker::make('date_fin')
                            ->label('Date de fin')
                            ->required()
                            ->displayFormat('d/m/Y')
                            ->afterOrEqual('date_depart'),
                    ])
                    ->columns(2),
            ]);
    }
}
