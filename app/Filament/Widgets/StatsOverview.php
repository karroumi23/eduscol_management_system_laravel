<?php

namespace App\Filament\Widgets;

use App\Models\Dossier;
use App\Models\Formateur;
use App\Models\Formation;
use App\Models\Participant;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsOverview extends StatsOverviewWidget
{
    protected static ?int $sort = 1;

    protected function getStats(): array
    {
        $totalParticipants = Participant::count();
        $totalDossiers = Dossier::count();
        $totalFormations = Formation::count();
        $totalFormateurs = Formateur::count();

        $chiffreAffaires = Dossier::sum('prix_formation');
        $resteAPayer = Dossier::selectRaw('SUM(prix_formation - acompte)')->value('SUM(prix_formation - acompte)') ?? 0;

        $dossiersThisMoth = Dossier::whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->count();

        $formationsActives = Formation::where('date_fin', '>=', now()->toDateString())->count();

        return [
            Stat::make('Participants', $totalParticipants)
                ->description('Total inscrits')
                ->descriptionIcon('heroicon-o-users')
                ->color('primary')
                ->chart(
                    Participant::selectRaw('COUNT(*) as count')
                        ->whereMonth('created_at', '>=', now()->subMonths(6)->month)
                        ->groupBy('created_at')
                        ->pluck('count')
                        ->toArray()
                ),

            Stat::make('Dossiers', $totalDossiers)
                ->description("{$dossiersThisMoth} ce mois-ci")
                ->descriptionIcon('heroicon-o-document-text')
                ->color('info'),

            Stat::make('Formations actives', $formationsActives)
                ->description("{$totalFormations} au total")
                ->descriptionIcon('heroicon-o-academic-cap')
                ->color('success'),

            Stat::make('Formateurs', $totalFormateurs)
                ->description('Formateurs disponibles')
                ->descriptionIcon('heroicon-o-user-circle')
                ->color('warning'),

            Stat::make('Chiffre d\'affaires', number_format($chiffreAffaires, 2, ',', ' ').' MAD')
                ->description('Total facturé')
                ->descriptionIcon('heroicon-o-banknotes')
                ->color('success'),

            Stat::make('Reste à encaisser', number_format($resteAPayer, 2, ',', ' ').' MAD')
                ->description('Total impayé')
                ->descriptionIcon('heroicon-o-exclamation-circle')
                ->color('danger'),
        ];
    }
}
