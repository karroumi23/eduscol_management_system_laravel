<?php

namespace App\Filament\Resources\Formations\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Actions\ViewAction;
use Filament\Forms\Components\DatePicker;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class FormationsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('titre')
                    ->label('Titre')
                    ->searchable()
                    ->sortable(),
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
                    ->color(fn (string $state) => match ($state) {
                        'TRM' => 'info',
                        'TRV' => 'success',
                    }),
                TextColumn::make('formateur.nom_complet')
                    ->label('Formateur')
                    ->searchable(['nom', 'prenom']),
                TextColumn::make('dossiers_count')
                    ->label('Participants')
                    ->counts('dossiers')
                    ->sortable(),
                TextColumn::make('prix')
                    ->label('Prix')
                    ->money('MAD')
                    ->sortable(),
                TextColumn::make('date_depart')
                    ->label('Début')
                    ->date('d/m/Y')
                    ->sortable(),
                TextColumn::make('date_fin')
                    ->label('Fin')
                    ->date('d/m/Y')
                    ->sortable(),
                TextColumn::make('createdBy.name')
                    ->label('Créé par')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('type_formation')
                    ->label('Type de formation')
                    ->options(['FQIMO' => 'FQIMO', 'FCO' => 'FCO']),
                SelectFilter::make('categorie_formation')
                    ->label('Catégorie')
                    ->options(['TRM' => 'TRM', 'TRV' => 'TRV']),
                Filter::make('dates')
                    ->label('Période')
                    ->form([
                        DatePicker::make('du')->label('Début après le')->displayFormat('d/m/Y'),
                        DatePicker::make('au')->label('Fin avant le')->displayFormat('d/m/Y'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when($data['du'], fn ($q, $d) => $q->whereDate('date_depart', '>=', $d))
                            ->when($data['au'], fn ($q, $d) => $q->whereDate('date_fin', '<=', $d));
                    }),
                TrashedFilter::make(),
            ])
            ->recordActions([
                ViewAction::make(),
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                    ForceDeleteBulkAction::make(),
                    RestoreBulkAction::make(),
                ]),
            ]);
    }
}
