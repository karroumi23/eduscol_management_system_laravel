<?php

namespace App\Filament\Resources\Paiements\Pages;

use App\Filament\Resources\Paiements\PaiementResource;
use Filament\Resources\Pages\CreateRecord;

class CreatePaiement extends CreateRecord
{
    protected static string $resource = PaiementResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['cree_par'] = auth()->id();

        return $data;
    }
}
