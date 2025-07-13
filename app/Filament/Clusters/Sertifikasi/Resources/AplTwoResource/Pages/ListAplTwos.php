<?php

namespace App\Filament\Clusters\Sertifikasi\Resources\AplTwoResource\Pages;

use App\Filament\Clusters\Sertifikasi\Resources\AplTwoResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListAplTwos extends ListRecords
{
    protected static string $resource = AplTwoResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
