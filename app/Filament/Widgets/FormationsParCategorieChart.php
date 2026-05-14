<?php

namespace App\Filament\Widgets;

use App\Models\Dossier;
use Filament\Widgets\ChartWidget;

class FormationsParCategorieChart extends ChartWidget
{
    protected static ?int $sort = 2;

    protected ?string $heading = 'Dossiers par catégorie & type';

    protected ?string $description = 'Répartition des dossiers TRM / TRV et FQIMO / FCO.';

    protected ?string $maxHeight = '280px';

    protected int|string|array $columnSpan = 1;

    protected function getData(): array
    {
        $trm = Dossier::where('categorie_formation', 'TRM')->count();
        $trv = Dossier::where('categorie_formation', 'TRV')->count();
        $fqimo = Dossier::where('type_formation', 'FQIMO')->count();
        $fco = Dossier::where('type_formation', 'FCO')->count();

        return [
            'datasets' => [
                [
                    'data' => [$trm, $trv, $fqimo, $fco],
                    'backgroundColor' => [
                        'rgba(59, 130, 246, 0.8)',
                        'rgba(34, 197, 94, 0.8)',
                        'rgba(251, 191, 36, 0.8)',
                        'rgba(239, 68, 68, 0.8)',
                    ],
                    'borderWidth' => 0,
                    'hoverOffset' => 6,
                ],
            ],
            'labels' => ['TRM', 'TRV', 'FQIMO', 'FCO'],
        ];
    }

    protected function getType(): string
    {
        return 'doughnut';
    }
}
