<?php

namespace App\Filament\Resources\Participants\Pages;

use App\Filament\Resources\Participants\ParticipantResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListParticipants extends ListRecords
{
    protected static string $resource = ParticipantResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()
                ->label('Nouveau Participant'),
        ];
    }

    public function getTitle(): string
    {
        return 'Liste des Participants';
    }

    public function getBreadcrumb(): string
    {
        return 'Liste';
    }
}

