<?php

namespace App\Filament\Resources\Dossiers\Schemas;

use App\Models\Participant;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class DossierForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Participant')
                    ->description('Sélectionnez le participant pour pré-remplir automatiquement ses informations.')
                    ->icon('heroicon-o-user-group')
                    ->schema([
                        Select::make('participant_id')
                            ->label('Participant')
                            ->options(Participant::query()->pluck('nom', 'id'))
                            ->searchable()
                            ->required()
                            ->live()
                            ->afterStateUpdated(function ($state, callable $set) {
                                if (! $state) {
                                    return;
                                }
                                $participant = Participant::find($state);
                                if ($participant) {
                                    $set('nom', $participant->nom);
                                    $set('cin', $participant->cin);
                                    $set('telephone', $participant->telephone);
                                    $set('permis', $participant->num_permis);
                                    $set('categorie_formation', $participant->categorie_formation);
                                }
                            })
                            ->columnSpanFull(),
                    ]),

                Section::make('Informations personnelles')
                    ->description('Données d\'identité du participant pour ce dossier.')
                    ->icon('heroicon-o-identification')
                    ->schema([
                        FileUpload::make('photo')
                            ->label('Photo')
                            ->image()
                            ->imageEditor()
                            ->directory('dossiers/photos')
                            ->avatar()
                            ->columnSpanFull(),
                        TextInput::make('nom')
                            ->label('Nom complet')
                            ->maxLength(255),
                        TextInput::make('cin')
                            ->label('CIN')
                            ->maxLength(255),
                        TextInput::make('telephone')
                            ->label('Téléphone')
                            ->tel()
                            ->maxLength(255),
                        DatePicker::make('date_naissance')
                            ->label('Date de naissance')
                            ->displayFormat('d/m/Y'),
                        TextInput::make('permis')
                            ->label('Numéro de permis')
                            ->maxLength(255),
                        Select::make('categorie_formation')
                            ->label('Catégorie de formation')
                            ->options([
                                'TRM' => 'TRM',
                                'TRV' => 'TRV',
                            ])
                            ->native(false),
                    ])
                    ->columns(2),

                Section::make('Détails de la formation')
                    ->description('Type, formateur, tarification et planning de la formation.')
                    ->icon('heroicon-o-academic-cap')
                    ->schema([
                        Select::make('type_formation')
                            ->label('Type de formation')
                            ->options([
                                'FQIMO' => 'FQIMO',
                                'FCO' => 'FCO',
                            ])
                            ->required()
                            ->native(false),
                        TextInput::make('nom_formateur')
                            ->label('Formateur')
                            ->required()
                            ->maxLength(255),
                        TextInput::make('prix_formation')
                            ->label('Prix (MAD)')
                            ->required()
                            ->numeric()
                            ->prefix('MAD'),
                        TextInput::make('acompte')
                            ->label('Acompte (MAD)')
                            ->required()
                            ->numeric()
                            ->default(0)
                            ->prefix('MAD'),
                        DatePicker::make('date_depart_formation')
                            ->label('Date de début')
                            ->required()
                            ->displayFormat('d/m/Y'),
                        DatePicker::make('date_fin_formation')
                            ->label('Date de fin')
                            ->required()
                            ->displayFormat('d/m/Y')
                            ->afterOrEqual('date_depart_formation'),
                    ])
                    ->columns(2),
            ]);
    }
}
