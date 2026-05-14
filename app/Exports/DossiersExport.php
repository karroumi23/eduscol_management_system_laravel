<?php

namespace App\Exports;

use App\Models\Dossier;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithTitle;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class DossiersExport implements FromQuery, ShouldAutoSize, WithHeadings, WithMapping, WithStyles, WithTitle
{
    public function query()
    {
        return Dossier::query()
            ->with(['participant', 'formateur', 'paiements'])
            ->latest();
    }

    public function title(): string
    {
        return 'Dossiers';
    }

    public function headings(): array
    {
        return [
            'ID',
            'Participant',
            'CIN',
            'Téléphone',
            'Type formation',
            'Catégorie',
            'Formateur',
            'Prix (MAD)',
            'Encaissé (MAD)',
            'Solde (MAD)',
            'Statut',
            'Début formation',
            'Fin formation',
            'Date création',
        ];
    }

    public function map($dossier): array
    {
        $encaisse = $dossier->paiements->sum('montant');
        $solde = (float) $dossier->prix_formation - (float) $encaisse;

        $statut = match (true) {
            $encaisse <= 0 => 'Impayé',
            $encaisse >= (float) $dossier->prix_formation => 'Payé',
            default => 'Partiel',
        };

        return [
            $dossier->id,
            $dossier->participant?->nom ?? $dossier->nom,
            $dossier->cin,
            $dossier->telephone,
            $dossier->type_formation,
            $dossier->categorie_formation,
            $dossier->formateur?->nom_complet ?? '-',
            number_format((float) $dossier->prix_formation, 2, '.', ''),
            number_format((float) $encaisse, 2, '.', ''),
            number_format($solde, 2, '.', ''),
            $statut,
            $dossier->date_depart_formation?->format('d/m/Y'),
            $dossier->date_fin_formation?->format('d/m/Y'),
            $dossier->created_at->format('d/m/Y'),
        ];
    }

    public function styles(Worksheet $sheet): array
    {
        return [
            1 => ['font' => ['bold' => true]],
        ];
    }
}
