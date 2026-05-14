<?php

namespace App\Filament\Resources\Paiements\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;

class PaiementsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('dossier.participant.nom')
                    ->label('Participant')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('dossier.type_formation')
                    ->label('Formation')
                    ->badge()
                    ->color(fn (string $state) => match ($state) {
                        'FQIMO' => 'warning',
                        'FCO' => 'success',
                    }),
                TextColumn::make('montant')
                    ->label('Montant')
                    ->money('MAD')
                    ->sortable(),
                TextColumn::make('mode_paiement')
                    ->label('Mode')
                    ->badge()
                    ->color(fn (string $state) => match ($state) {
                        'espece' => 'success',
                        'virement' => 'info',
                        'cheque' => 'warning',
                    })
                    ->formatStateUsing(fn (string $state) => match ($state) {
                        'espece' => 'Espèce',
                        'virement' => 'Virement',
                        'cheque' => 'Chèque',
                    }),
                TextColumn::make('date_paiement')
                    ->label('Date')
                    ->date('d/m/Y')
                    ->sortable(),
                TextColumn::make('note')
                    ->label('Note')
                    ->limit(40)
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('createdBy.name')
                    ->label('Saisi par')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('created_at')
                    ->label('Créé le')
                    ->dateTime('d/m/Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('mode_paiement')
                    ->label('Mode de paiement')
                    ->options([
                        'espece' => 'Espèce',
                        'virement' => 'Virement',
                        'cheque' => 'Chèque',
                    ]),
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
