<?php

namespace App\Filament\Resources\Formations\Pages;

use App\Exports\FormationsExport;
use App\Filament\Resources\Formations\FormationResource;
use Filament\Actions\Action;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;
use Maatwebsite\Excel\Facades\Excel;

class ListFormations extends ListRecords
{
    protected static string $resource = FormationResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Action::make('export')
                ->label('Exporter Excel')
                ->icon('heroicon-o-arrow-down-tray')
                ->color('success')
                ->action(fn () => Excel::download(new FormationsExport, 'formations_'.now()->format('Ymd').'.xlsx')),
            CreateAction::make(),
        ];
    }
}
