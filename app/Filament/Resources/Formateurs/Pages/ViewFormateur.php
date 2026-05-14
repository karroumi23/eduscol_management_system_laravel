<?php

namespace App\Filament\Resources\Formateurs\Pages;

use App\Filament\Resources\Formateurs\FormateurResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewFormateur extends ViewRecord
{
    protected static string $resource = FormateurResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
