<?php

namespace App\Filament\Clusters\DataMaster\Resources\CertificationSchemeResource\Pages;

use App\Filament\Clusters\DataMaster\Resources\CertificationSchemeResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditCertificationScheme extends EditRecord
{
    protected static string $resource = CertificationSchemeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
