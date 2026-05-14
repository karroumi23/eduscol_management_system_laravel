<?php

namespace App\Filament\Widgets;

use App\Models\Dossier;
use App\Models\Paiement;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Carbon;

class PaiementsParMoisChart extends ChartWidget
{
    protected static ?int $sort = 2;

    protected ?string $heading = 'Encaissements vs Facturation';

    protected ?string $description = 'Comparatif mensuel des montants facturés et encaissés (6 derniers mois).';

    protected ?string $maxHeight = '280px';

    protected int|string|array $columnSpan = 2;

    protected function getData(): array
    {
        $months = collect(range(5, 0))->map(fn ($i) => now()->subMonths($i));

        $facture = $months->map(fn (Carbon $m) => Dossier::query()
            ->whereBetween('created_at', [$m->copy()->startOfMonth(), $m->copy()->endOfMonth()])
            ->sum('prix_formation')
        );

        $encaisse = $months->map(fn (Carbon $m) => Paiement::query()
            ->whereBetween('date_paiement', [$m->copy()->startOfMonth(), $m->copy()->endOfMonth()])
            ->sum('montant')
        );

        $labels = $months->map(fn (Carbon $m) => $m->format('M Y'));

        return [
            'datasets' => [
                [
                    'label' => 'Facturé (MAD)',
                    'data' => $facture->values()->toArray(),
                    'backgroundColor' => 'rgba(99, 102, 241, 0.15)',
                    'borderColor' => 'rgb(99, 102, 241)',
                    'borderWidth' => 2,
                    'fill' => true,
                    'tension' => 0.4,
                ],
                [
                    'label' => 'Encaissé (MAD)',
                    'data' => $encaisse->values()->toArray(),
                    'backgroundColor' => 'rgba(34, 197, 94, 0.15)',
                    'borderColor' => 'rgb(34, 197, 94)',
                    'borderWidth' => 2,
                    'fill' => true,
                    'tension' => 0.4,
                ],
            ],
            'labels' => $labels->toArray(),
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }
}
