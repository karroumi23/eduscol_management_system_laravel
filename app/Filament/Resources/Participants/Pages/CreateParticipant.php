<?php

namespace App\Filament\Resources\Participants\Pages;

use App\Filament\Resources\Participants\ParticipantResource;
use Filament\Resources\Pages\CreateRecord;

class CreateParticipant extends CreateRecord
{
    protected static string $resource = ParticipantResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['cree_par'] = auth()->id();

        return $data;
    }
}
