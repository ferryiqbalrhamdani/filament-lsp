<?php

namespace App\Filament\Clusters\DataMaster\Resources\DocumentTypeResource\Pages;

use Filament\Actions;
use Filament\Support\Enums\MaxWidth;
use Filament\Resources\Pages\ManageRecords;
use App\Filament\Clusters\DataMaster\Resources\DocumentTypeResource;

class ManageDocumentTypes extends ManageRecords
{
    protected static string $resource = DocumentTypeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                // ->slideOver()
                ->modalWidth(MaxWidth::Medium),
        ];
    }
}
