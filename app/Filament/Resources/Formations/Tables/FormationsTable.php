<?php

namespace App\Filament\Resources\Formations\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

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
                TextColumn::make('created_at')
                    ->label('Créé le')
                    ->dateTime('d/m/Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('type_formation')
                    ->label('Type de formation')
                    ->options([
                        'FQIMO' => 'FQIMO',
                        'FCO' => 'FCO',
                    ]),
                SelectFilter::make('categorie_formation')
                    ->label('Catégorie')
                    ->options([
                        'TRM' => 'TRM',
                        'TRV' => 'TRV',
                    ]),
            ])
            ->recordActions([
                ViewAction::make(),
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
