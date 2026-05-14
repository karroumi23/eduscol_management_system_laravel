<?php

namespace App\Filament\Resources\Paiements\Schemas;

use App\Models\Dossier;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class PaiementForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Dossier concerné')
                    ->description('Sélectionnez le dossier auquel ce paiement est rattaché.')
                    ->icon('heroicon-o-document-text')
                    ->schema([
                        Select::make('dossier_id')
                            ->label('Dossier')
                            ->options(
                                Dossier::query()
                                    ->with('participant')
                                    ->get()
                                    ->mapWithKeys(fn (Dossier $d) => [
                                        $d->id => "{$d->participant?->nom} — {$d->type_formation} ({$d->date_depart_formation?->format('d/m/Y')})",
                                    ])
                            )
                            ->searchable()
                            ->required()
                            ->native(false)
                            ->columnSpanFull(),
                    ]),

                Section::make('Détails du paiement')
                    ->description('Montant, mode de règlement et date.')
                    ->icon('heroicon-o-banknotes')
                    ->schema([
                        TextInput::make('montant')
                            ->label('Montant (MAD)')
                            ->required()
                            ->numeric()
                            ->prefix('MAD')
                            ->minValue(0.01),
                        Select::make('mode_paiement')
                            ->label('Mode de paiement')
                            ->options([
                                'espece' => 'Espèce',
                                'virement' => 'Virement',
                                'cheque' => 'Chèque',
                            ])
                            ->required()
                            ->native(false),
                        DatePicker::make('date_paiement')
                            ->label('Date du paiement')
                            ->required()
                            ->default(now())
                            ->displayFormat('d/m/Y'),
                        Textarea::make('note')
                            ->label('Note')
                            ->rows(2)
                            ->columnSpanFull(),
                    ])
                    ->columns(2),
            ]);
    }
}
