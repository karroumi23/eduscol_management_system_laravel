<?php

namespace App\Filament\Widgets;

use App\Filament\Resources\Dossiers\DossierResource;
use App\Models\Dossier;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget;
use Illuminate\Database\Eloquent\Builder;

class RecentDossiers extends TableWidget
{
    protected static ?int $sort = 3;

    protected static ?string $heading = 'Dossiers récents';

    protected int|string|array $columnSpan = 'full';

    public function table(Table $table): Table
    {
        return $table
            ->query(fn (): Builder => Dossier::query()->latest()->limit(8))
            ->columns([
                TextColumn::make('participant.nom')
                    ->label('Participant')
                    ->searchable()
                    ->url(fn (Dossier $record): string => DossierResource::getUrl('view', ['record' => $record])),
                TextColumn::make('type_formation')
                    ->label('Type')
                    ->badge()
                    ->color(fn (string $state) => match ($state) {
                        'FQIMO' => 'warning',
                        'FCO' => 'success',
                    }),
                TextColumn::make('categorie_formation')
                    ->label('Catégorie')
                    ->badge()
                    ->color(fn (?string $state) => match ($state) {
                        'TRM' => 'info',
                        'TRV' => 'success',
                        default => 'gray',
                    }),
                TextColumn::make('nom_formateur')
                    ->label('Formateur'),
                TextColumn::make('prix_formation')
                    ->label('Prix')
                    ->money('MAD'),
                TextColumn::make('acompte')
                    ->label('Acompte')
                    ->money('MAD'),
                TextColumn::make('date_depart_formation')
                    ->label('Début')
                    ->date('d/m/Y'),
                TextColumn::make('date_fin_formation')
                    ->label('Fin')
                    ->date('d/m/Y'),
                TextColumn::make('created_at')
                    ->label('Créé le')
                    ->dateTime('d/m/Y H:i')
                    ->sortable(),
            ])
            ->recordActions([])
            ->toolbarActions([])
            ->paginated(false);
    }
}
