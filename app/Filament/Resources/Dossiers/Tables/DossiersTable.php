<?php

namespace App\Filament\Resources\Dossiers\Tables;

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

class DossiersTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('participant.nom')
                    ->label('Participant')
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
                    ->color(fn (?string $state) => match ($state) {
                        'TRM' => 'info',
                        'TRV' => 'success',
                        default => 'gray',
                    }),
                TextColumn::make('formateur.nom_complet')
                    ->label('Formateur')
                    ->searchable(['nom', 'prenom']),
                TextColumn::make('prix_formation')
                    ->label('Prix')
                    ->money('MAD')
                    ->sortable(),
                TextColumn::make('statut_paiement')
                    ->label('Paiement')
                    ->badge()
                    ->color(fn (string $state) => match ($state) {
                        'paye' => 'success',
                        'partiel' => 'warning',
                        'impaye' => 'danger',
                    })
                    ->formatStateUsing(fn (string $state) => match ($state) {
                        'paye' => 'Payé',
                        'partiel' => 'Partiel',
                        'impaye' => 'Impayé',
                    }),
                TextColumn::make('date_depart_formation')
                    ->label('Début')
                    ->date('d/m/Y')
                    ->sortable(),
                TextColumn::make('date_fin_formation')
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
                    ->options(['FQIMO' => 'FQIMO', 'FCO' => 'FCO']),
                SelectFilter::make('categorie_formation')
                    ->label('Catégorie')
                    ->options(['TRM' => 'TRM', 'TRV' => 'TRV']),
                Filter::make('date_depart_formation')
                    ->label('Période de formation')
                    ->form([
                        DatePicker::make('du')->label('Du')->displayFormat('d/m/Y'),
                        DatePicker::make('au')->label('Au')->displayFormat('d/m/Y'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when($data['du'], fn ($q, $d) => $q->whereDate('date_depart_formation', '>=', $d))
                            ->when($data['au'], fn ($q, $d) => $q->whereDate('date_fin_formation', '<=', $d));
                    }),
                Filter::make('created_at')
                    ->label('Date de création')
                    ->form([
                        DatePicker::make('du')->label('Du')->displayFormat('d/m/Y'),
                        DatePicker::make('au')->label('Au')->displayFormat('d/m/Y'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when($data['du'], fn ($q, $d) => $q->whereDate('created_at', '>=', $d))
                            ->when($data['au'], fn ($q, $d) => $q->whereDate('created_at', '<=', $d));
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
