<?php

namespace App\Filament\Resources\Participants\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Actions\ViewAction;
use Filament\Forms\Components\DatePicker;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

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
                TextColumn::make('dossiers_count')
                    ->label('Dossiers')
                    ->counts('dossiers')
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
                SelectFilter::make('categorie_formation')
                    ->label('Catégorie')
                    ->options(['TRM' => 'TRM', 'TRV' => 'TRV']),
                Filter::make('created_at')
                    ->label('Date d\'inscription')
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
