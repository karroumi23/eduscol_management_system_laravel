<?php

namespace App\Filament\Resources\Participants;

use App\Filament\Resources\Participants\Pages;
use Filament\Actions\Action;
use App\Filament\Resources\Dossiers\DossierResource;
use App\Models\Participant;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Enums\FontWeight;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Illuminate\Support\Facades\Auth;

class ParticipantResource extends Resource
{
    protected static ?string $model = Participant::class;
    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-users';
    protected static ?string $navigationLabel = 'Participants';
    protected static ?string $modelLabel = 'Participant';
    protected static ?string $pluralModelLabel = 'Participants';
    protected static ?string $createButtonLabel = 'Nouveau Participant';
    protected static ?int $navigationSort = 1;

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('Informations personnelles')
                ->schema([
                    FileUpload::make('photo')
                        ->label('Photo')
                        ->image()
                        ->directory('participants')
                        ->columnSpanFull(),
                    TextInput::make('nom')
                        ->label('Nom complet')
                        ->required()
                        ->maxLength(150),
                    TextInput::make('cin')
                        ->label('CIN')
                        ->required()
                        ->unique(ignoreRecord: true)
                        ->maxLength(20),
                    TextInput::make('telephone')
                        ->label('Téléphone')
                        ->required()
                        ->tel()
                        ->maxLength(20),
                    TextInput::make('num_permis')
                        ->label('N° Permis')
                        ->required()
                        ->unique(ignoreRecord: true)
                        ->maxLength(50),
                    Select::make('categorie_formation')
                        ->label('Catégorie de formation')
                        ->options([
                            'TRM' => 'Transport Routier de Marchandises (TRM)',
                            'TRV' => 'Transport Routier de Voyageurs (TRV)',
                        ])
                        ->required(),
                    Hidden::make('cree_par')
                        ->default(fn () => Auth::id()),
                ])->columns(2),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->searchPlaceholder('Rechercher un participant...')
            ->striped()
            ->defaultSort('created_at', 'desc')
            ->columns([
                Tables\Columns\TextColumn::make('id')
                    ->label('ID')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                Tables\Columns\ImageColumn::make('photo')
                    ->label('')
                    ->circular()
                    ->defaultImageUrl(fn (Participant $record): string => 'https://ui-avatars.com/api/?name='.urlencode($record->nom).'&background=2F6FA5&color=fff')
                    ->extraImgAttributes(['class' => 'ring-2 ring-[#2F6FA5]/20']),

                Tables\Columns\TextColumn::make('nom')
                    ->label('Participant')
                    ->weight(FontWeight::SemiBold)
                    ->description(fn (Participant $record): string => 'CIN : '.$record->cin)
                    ->searchable(['nom', 'cin'])
                    ->sortable(),

                Tables\Columns\TextColumn::make('telephone')
                    ->label('Téléphone')
                    // ->icon('heroicon-o-phone')
                    ->iconColor('primary')
                    ->toggleable(),

                Tables\Columns\TextColumn::make('num_permis')
                    ->label('N° Permis')
                    ->icon('heroicon-o-identification')
                    ->iconColor('primary')
                    ->searchable()
                    ->toggleable(),

                Tables\Columns\TextColumn::make('categorie_formation')
                    ->label('Catégorie')
                    ->badge()
                    ->searchable()
                    ->color(fn (string $state): string => match ($state) {
                        'TRM' => 'primary',
                        'TRV' => 'warning',
                        default => 'gray',
                    }),

                Tables\Columns\TextColumn::make('dossiers_count')
                    ->label('Dossiers')
                    ->counts('dossiers')
                    ->badge()
                    ->color(fn (int $state): string => $state > 0 ? 'primary' : 'gray')
                    ->sortable(),

                Tables\Columns\TextColumn::make('createdBy.name')
                    ->label('Créé par')
                    ->icon('heroicon-o-user-circle')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Ajouté')
                    ->since()
                    ->dateTimeTooltip()
                    ->sortable()
                    ->toggleable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('categorie_formation')
                    ->label('Catégorie')
                    ->options([
                        'TRM' => 'TRM',
                        'TRV' => 'TRV',
                    ]),
            ])
            ->recordActions([
                Action::make('create_dossier')
                    ->label('')
                    ->tooltip('Nouveau dossier')
                    ->icon('heroicon-o-plus-circle')
                    ->color('warning')
                    ->url(fn (Participant $record): string =>
                        DossierResource::getUrl('create') . '?participant_id=' . $record->id
                    ),
                Action::make('view_dossiers')
                    ->label('')
                    ->tooltip('Voir dossiers')
                    ->icon('heroicon-o-folder-open')
                    ->color('primary')
                    ->visible(fn (Participant $record): bool => $record->dossiers_count > 0)
                    ->url(fn (Participant $record): string =>
                        \App\Filament\Resources\Dossiers\DossierResource::getUrl('index') . '?participant_id=' . $record->id
                    ),
                ViewAction::make()->label(''),
                EditAction::make()->label(''),
                DeleteAction::make()->label(''),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ])
            ->emptyStateIcon('heroicon-o-users')
            ->emptyStateHeading('Aucun participant pour le moment')
            ->emptyStateDescription('Ajoutez votre premier participant pour commencer à créer des dossiers de formation.')
            ->emptyStateActions([
                Action::make('create')
                    ->label('Nouveau Participant')
                    ->icon('heroicon-o-plus')
                    ->color('primary')
                    ->url(fn (): string => static::getUrl('create')),
            ]);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListParticipants::route('/'),
            'create' => Pages\CreateParticipant::route('/create'),
            'edit'   => Pages\EditParticipant::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): \Illuminate\Database\Eloquent\Builder
    {
        return parent::getEloquentQuery()->withCount('dossiers');
    }
}