<?php

namespace App\Exports;

use App\Models\Participant;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithTitle;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class ParticipantsExport implements FromQuery, ShouldAutoSize, WithHeadings, WithMapping, WithStyles, WithTitle
{
    public function query()
    {
        return Participant::query()->with('createdBy')->latest();
    }

    public function title(): string
    {
        return 'Participants';
    }

    public function headings(): array
    {
        return [
            'ID',
            'Nom complet',
            'CIN',
            'Téléphone',
            'N° Permis',
            'Catégorie',
            'Créé par',
            'Date inscription',
        ];
    }

    public function map($participant): array
    {
        return [
            $participant->id,
            $participant->nom,
            $participant->cin,
            $participant->telephone,
            $participant->num_permis,
            $participant->categorie_formation,
            $participant->createdBy?->name ?? '-',
            $participant->created_at->format('d/m/Y'),
        ];
    }

    public function styles(Worksheet $sheet): array
    {
        return [
            1 => ['font' => ['bold' => true]],
        ];
    }
}
