<?php

namespace App\Filament\Resources\Formateurs\Pages;

use App\Filament\Resources\Formateurs\FormateurResource;
use Filament\Resources\Pages\CreateRecord;

class CreateFormateur extends CreateRecord
{
    protected static string $resource = FormateurResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['cree_par'] = auth()->id();

        return $data;
    }
}
