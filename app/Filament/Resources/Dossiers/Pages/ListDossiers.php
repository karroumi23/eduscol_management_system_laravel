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
        $searchNom = request()->query('search_nom');
        $searchCin = request()->query('search_cin');
        $filterCategorie = request()->query('categorie_formation');
        $filterType = request()->query('type_formation');

        $query = Dossier::with(['participant', 'createdBy'])->latest();

        if ($participantId) {
            $query->where('participant_id', $participantId);
        }

        if ($searchNom) {
            $query->whereHas('participant', fn($q) =>
                $q->where('nom', 'like', '%' . $searchNom . '%')
            );
        }

        if ($searchCin) {
            $query->whereHas('participant', fn($q) =>
                $q->where('cin', 'like', '%' . $searchCin . '%')
            );
        }

        if ($filterCategorie) {
            $query->where('categorie_formation', $filterCategorie);
        }

        if ($filterType) {
            $query->where('type_formation', $filterType);
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