<?php

namespace App\Filament\Clusters\Sertifikasi\Resources\AplOneResource\Pages;

use App\Filament\Clusters\Sertifikasi\Resources\AplOneResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListAplOnes extends ListRecords
{
    protected static string $resource = AplOneResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
