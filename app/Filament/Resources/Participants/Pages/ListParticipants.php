<?php

namespace App\Filament\Resources\Participants\Pages;

use App\Exports\ParticipantsExport;
use App\Filament\Resources\Participants\ParticipantResource;
use Filament\Actions\Action;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;
use Maatwebsite\Excel\Facades\Excel;

class ListParticipants extends ListRecords
{
    protected static string $resource = ParticipantResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Action::make('export')
                ->label('Exporter Excel')
                ->icon('heroicon-o-arrow-down-tray')
                ->color('success')
                ->action(fn () => Excel::download(new ParticipantsExport, 'participants_'.now()->format('Ymd').'.xlsx')),
            CreateAction::make(),
        ];
    }
}
