<?php

namespace App\Filament\Widgets;

use App\Models\Dossier;
use App\Models\Formateur;
use App\Models\Formation;
use App\Models\Paiement;
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
        $totalFormateurs = Formateur::count();
        $formationsActives = Formation::actives()->count();
        $totalFormations = Formation::count();

        $dossiersThisMois = Dossier::duMois()->count();

        $totalFacture = Dossier::sum('prix_formation');
        $totalEncaisse = Paiement::sum('montant');
        $resteAPayer = max(0, (float) $totalFacture - (float) $totalEncaisse);

        $dossiersPayes = Dossier::whereHas('paiements', function ($q) {
            $q->selectRaw('dossier_id, SUM(montant) as total')
                ->groupBy('dossier_id')
                ->havingRaw('SUM(montant) >= dossiers.prix_formation');
        })->count();

        return [
            Stat::make('Participants', $totalParticipants)
                ->description('Total inscrits')
                ->descriptionIcon('heroicon-o-users')
                ->color('primary'),

            Stat::make('Dossiers', $totalDossiers)
                ->description("{$dossiersThisMois} ce mois-ci")
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

            Stat::make('Total encaissé', number_format($totalEncaisse, 2, ',', ' ').' MAD')
                ->description('Sur '.number_format($totalFacture, 2, ',', ' ').' MAD facturé')
                ->descriptionIcon('heroicon-o-banknotes')
                ->color('success'),

            Stat::make('Reste à encaisser', number_format($resteAPayer, 2, ',', ' ').' MAD')
                ->description("{$dossiersPayes} dossier(s) soldés")
                ->descriptionIcon('heroicon-o-exclamation-circle')
                ->color($resteAPayer > 0 ? 'danger' : 'success'),
        ];
    }
}
