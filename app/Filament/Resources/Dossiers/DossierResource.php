<?php

namespace App\Filament\Resources\Dossiers;


use App\Filament\Resources\Dossiers\Pages;

use App\Models\Dossier;
use App\Models\Participant;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Auth;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;

class DossierResource extends Resource
{
    protected static ?string $model = Dossier::class;
    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-folder';
    protected static ?string $navigationLabel = 'Dossiers';
    protected static ?string $modelLabel = 'Dossier';
    protected static ?int $navigationSort = 2;

    public static function form(Schema $schema): Schema
{
    return $schema->components([
        Section::make('Participant')
            ->schema([
                Select::make('participant_id')
                    ->label('Sélectionner le participant')
                    ->options(Participant::all()->pluck('nom', 'id'))
                    ->searchable()
                    ->required()
                    ->live()
                    ->afterStateUpdated(function ($state, callable $set) {
                        $participant = Participant::find($state);
                        if ($participant) {
                            $set('categorie_formation', $participant->categorie_formation);
                        }
                    })
                    ->columnSpanFull(),

                // Read-only display fields from participant
                TextInput::make('participant_nom')
                    ->label('Nom complet')
                    ->disabled()
                    ->dehydrated(false)
                    ->placeholder('Sélectionnez un participant')
                    ->formatStateUsing(fn ($state, $record) =>
                        $record?->participant?->nom ?? ''
                    ),
                TextInput::make('participant_cin')
                    ->label('CIN')
                    ->disabled()
                    ->dehydrated(false)
                    ->formatStateUsing(fn ($state, $record) =>
                        $record?->participant?->cin ?? ''
                    ),
                TextInput::make('participant_tel')
                    ->label('Téléphone')
                    ->disabled()
                    ->dehydrated(false)
                    ->formatStateUsing(fn ($state, $record) =>
                        $record?->participant?->telephone ?? ''
                    ),
                TextInput::make('participant_permis')
                    ->label('N° Permis')
                    ->disabled()
                    ->dehydrated(false)
                    ->formatStateUsing(fn ($state, $record) =>
                        $record?->participant?->num_permis ?? ''
                    ),
            ])->columns(2),

        Section::make('Informations de formation')
            ->schema([
                DatePicker::make('date_naissance')
                    ->label('Date de naissance')
                    ->required(),
                Select::make('categorie_formation')
                    ->label('Catégorie de formation')
                    ->options([
                        'TRM' => 'Transport Routier de Marchandises (TRM)',
                        'TRV' => 'Transport Routier de Voyageurs (TRV)',
                    ])
                    ->required(),
                Select::make('type_formation')
                    ->label('Type de formation')
                    ->options([
                        'FQIMO' => 'Première qualification (FQIMO)',
                        'FCO' => 'Formation continue (FCO)',
                    ])
                    ->required(),
                TextInput::make('nom_formateur')
                    ->label('Nom du formateur')
                    ->required()
                    ->maxLength(150),
                TextInput::make('prix_formation')
                    ->label('Prix de formation')
                    ->required()
                    ->numeric()
                    ->prefix('MAD'),
                TextInput::make('acompte')
                    ->label('Acompte versé')
                    ->numeric()
                    ->default(0)
                    ->prefix('MAD'),
                DatePicker::make('date_depart_formation')
                    ->label('Date de début')
                    ->required(),
                DatePicker::make('date_fin_formation')
                    ->label('Date de fin')
                    ->required(),
                Hidden::make('cree_par')
                    ->default(fn () => Auth::id()),
            ])->columns(2),
    ]);
}

    public static function table(Table $table): Table
    {
        return $table
        ->columns([
            Tables\Columns\TextColumn::make('id')
                ->label('ID')
                ->sortable(),
            Tables\Columns\ImageColumn::make('participant.photo')
                ->label('Photo')
                ->circular(),
            Tables\Columns\TextColumn::make('participant.nom')
                ->label('Participant')
                ->searchable()
                ->sortable(),
            Tables\Columns\TextColumn::make('participant.cin')
                ->label('CIN')
                ->searchable(),
            Tables\Columns\TextColumn::make('participant.telephone')
                ->label('Téléphone'),
            Tables\Columns\TextColumn::make('participant.num_permis')
                ->label('N° Permis'),
            Tables\Columns\TextColumn::make('categorie_formation')
                ->label('Catégorie')
                ->badge()
                ->color(fn (string $state): string => match ($state) {
                    'TRM' => 'primary',
                    'TRV' => 'success',
                    default => 'gray',
                }),
            Tables\Columns\TextColumn::make('type_formation')
                ->label('Type')
                ->badge()
                ->color(fn (string $state): string => match ($state) {
                    'FQIMO' => 'warning',
                    'FCO' => 'danger',
                    default => 'gray',
                }),
            Tables\Columns\TextColumn::make('nom_formateur')
                ->label('Formateur')
                ->searchable(),
            Tables\Columns\TextColumn::make('prix_formation')
                ->label('Prix (MAD)')
                ->sortable(),
            Tables\Columns\TextColumn::make('acompte')
                ->label('Acompte (MAD)'),
            Tables\Columns\TextColumn::make('date_depart_formation')
                ->label('Date début')
                ->date('d/m/Y')
                ->sortable(),
            Tables\Columns\TextColumn::make('date_fin_formation')
                ->label('Date fin')
                ->date('d/m/Y')
                ->sortable(),
            Tables\Columns\TextColumn::make('createdBy.name')
                ->label('Créé par'),
        ])
            ->filters([
                Tables\Filters\SelectFilter::make('categorie_formation')
                    ->label('Catégorie')
                    ->options([
                        'TRM' => 'TRM',
                        'TRV' => 'TRV',
                    ]),
                Tables\Filters\SelectFilter::make('type_formation')
                    ->label('Type de formation')
                    ->options([
                        'FQIMO' => 'FQIMO',
                        'FCO' => 'FCO',
                    ]),
            ])
            ->recordActions([
                ViewAction::make(),
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);

    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListDossiers::route('/'),
            'create' => Pages\CreateDossier::route('/create'),
            'edit' => Pages\EditDossier::route('/{record}/edit'),
        ];
    }
}

