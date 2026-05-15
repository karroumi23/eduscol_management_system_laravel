<?php

namespace App\Filament\Resources\Dossiers\Pages;

use App\Filament\Resources\Dossiers\DossierResource;
use App\Models\Dossier;
use Filament\Resources\Pages\ListRecords;

class ListDossiers extends ListRecords
{
    protected static string $resource = DossierResource::class;

    public function getViewData(): array
    {
        return [
            'records' => Dossier::with(['participant', 'createdBy'])
                ->latest()
                ->paginate(9),
        ];
    }

    public function getView(): string
    {
        return 'filament.resources.dossiers.list-dossiers';
    }
}