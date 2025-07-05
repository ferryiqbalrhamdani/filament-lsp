<?php

namespace App\Filament\Resources\CertificationListResource\Pages;

use App\Filament\Resources\CertificationListResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListCertificationLists extends ListRecords
{
    protected static string $resource = CertificationListResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
