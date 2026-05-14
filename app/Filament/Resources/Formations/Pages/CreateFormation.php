<?php

namespace App\Filament\Resources\Formations\Pages;

use App\Filament\Resources\Formations\FormationResource;
use Filament\Resources\Pages\CreateRecord;

class CreateFormation extends CreateRecord
{
    protected static string $resource = FormationResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['cree_par'] = auth()->id();

        return $data;
    }
}
