<?php

namespace App\Exports;

use App\Models\Formation;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithTitle;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class FormationsExport implements FromQuery, ShouldAutoSize, WithHeadings, WithMapping, WithStyles, WithTitle
{
    public function query()
    {
        return Formation::query()
            ->with(['formateur', 'dossiers'])
            ->latest();
    }

    public function title(): string
    {
        return 'Formations';
    }

    public function headings(): array
    {
        return [
            'ID',
            'Titre',
            'Type',
            'Catégorie',
            'Formateur',
            'Prix (MAD)',
            'Participants',
            'Date début',
            'Date fin',
            'Statut',
        ];
    }

    public function map($formation): array
    {
        return [
            $formation->id,
            $formation->titre,
            $formation->type_formation,
            $formation->categorie_formation,
            $formation->formateur?->nom_complet ?? '-',
            number_format((float) $formation->prix, 2, '.', ''),
            $formation->dossiers->count(),
            $formation->date_depart?->format('d/m/Y'),
            $formation->date_fin?->format('d/m/Y'),
            $formation->date_fin?->isPast() ? 'Terminée' : 'Active',
        ];
    }

    public function styles(Worksheet $sheet): array
    {
        return [
            1 => ['font' => ['bold' => true]],
        ];
    }
}
