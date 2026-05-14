<?php

namespace App\Filament\Resources\Dossiers\Pages;

use App\Exports\DossiersExport;
use App\Filament\Resources\Dossiers\DossierResource;
use Filament\Actions\Action;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;
use Maatwebsite\Excel\Facades\Excel;

class ListDossiers extends ListRecords
{
    protected static string $resource = DossierResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Action::make('export')
                ->label('Exporter Excel')
                ->icon('heroicon-o-arrow-down-tray')
                ->color('success')
                ->action(fn () => Excel::download(new DossiersExport, 'dossiers_'.now()->format('Ymd').'.xlsx')),
            CreateAction::make(),
        ];
    }
}
