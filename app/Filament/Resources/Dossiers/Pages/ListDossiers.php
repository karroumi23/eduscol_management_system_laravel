<?php

namespace App\Filament\Resources\Dossiers\Pages;

use App\Filament\Resources\Dossiers\DossierResource;
use App\Models\Dossier;
use App\Models\Participant;
use Filament\Resources\Pages\ListRecords;

class ListDossiers extends ListRecords
{
    protected static string $resource = DossierResource::class;

    public function getViewData(): array
    {
        $participantId = request()->query('participant_id');

        $query = Dossier::with(['participant', 'createdBy'])->latest();

        if ($participantId) {
            $query->where('participant_id', $participantId);
        }

        return [
            'records' => $query->paginate(9),
            'participant' => $participantId ? Participant::find($participantId) : null,
        ];
    }

    public function getView(): string
    {
        return 'filament.resources.dossiers.list-dossiers';
    }
}