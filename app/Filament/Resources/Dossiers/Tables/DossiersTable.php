<?php

namespace App\Filament\Resources\Dossiers\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class DossiersTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('participant_id')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('photo')
                    ->searchable(),
                TextColumn::make('nom')
                    ->searchable(),
                TextColumn::make('cin')
                    ->searchable(),
                TextColumn::make('telephone')
                    ->searchable(),
                TextColumn::make('date_naissance')
                    ->date()
                    ->sortable(),
                TextColumn::make('permis')
                    ->searchable(),
                TextColumn::make('categorie_formation')
                    ->badge(),
                TextColumn::make('type_formation')
                    ->badge(),
                TextColumn::make('nom_formateur')
                    ->searchable(),
                TextColumn::make('prix_formation')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('acompte')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('date_depart_formation')
                    ->date()
                    ->sortable(),
                TextColumn::make('date_fin_formation')
                    ->date()
                    ->sortable(),
                TextColumn::make('cree_par')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
