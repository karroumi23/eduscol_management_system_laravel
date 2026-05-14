<?php

namespace App\Filament\Widgets;

use App\Models\Dossier;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Carbon;

class DossiersParTypeChart extends ChartWidget
{
    protected static ?int $sort = 2;

    protected ?string $heading = 'Dossiers par mois';

    protected ?string $description = 'Évolution des dossiers sur les 6 derniers mois.';

    protected ?string $maxHeight = '280px';

    protected int|string|array $columnSpan = 2;

    protected function getData(): array
    {
        $months = collect(range(5, 0))->map(fn ($i) => now()->subMonths($i));

        $fqimoData = $months->map(fn (Carbon $month) => Dossier::query()
            ->where('type_formation', 'FQIMO')
            ->whereBetween('created_at', [$month->copy()->startOfMonth(), $month->copy()->endOfMonth()])
            ->count()
        );

        $fcoData = $months->map(fn (Carbon $month) => Dossier::query()
            ->where('type_formation', 'FCO')
            ->whereBetween('created_at', [$month->copy()->startOfMonth(), $month->copy()->endOfMonth()])
            ->count()
        );

        $labels = $months->map(fn (Carbon $m) => $m->format('M Y'));

        return [
            'datasets' => [
                [
                    'label' => 'FQIMO',
                    'data' => $fqimoData->values()->toArray(),
                    'backgroundColor' => 'rgba(251, 191, 36, 0.2)',
                    'borderColor' => 'rgb(251, 191, 36)',
                    'borderWidth' => 2,
                    'fill' => true,
                    'tension' => 0.4,
                ],
                [
                    'label' => 'FCO',
                    'data' => $fcoData->values()->toArray(),
                    'backgroundColor' => 'rgba(34, 197, 94, 0.2)',
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
