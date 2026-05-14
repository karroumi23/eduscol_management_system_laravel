<?php

namespace App\Filament\Resources\Participants\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class ParticipantsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('photo')
                    ->circular()
                    ->defaultImageUrl(fn () => 'https://ui-avatars.com/api/?name=?&color=7F9CF5&background=EBF4FF'),
                TextColumn::make('nom')
                    ->label('Nom complet')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('cin')
                    ->label('CIN')
                    ->searchable(),
                TextColumn::make('telephone')
                    ->label('Téléphone')
                    ->searchable(),
                TextColumn::make('num_permis')
                    ->label('N° Permis')
                    ->searchable(),
                TextColumn::make('categorie_formation')
                    ->label('Catégorie')
                    ->badge()
                    ->color(fn (string $state) => match ($state) {
                        'TRM' => 'info',
                        'TRV' => 'success',
                    }),
                TextColumn::make('createdBy.name')
                    ->label('Créé par')
                    ->sortable(),
                TextColumn::make('created_at')
                    ->label('Créé le')
                    ->dateTime('d/m/Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
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
