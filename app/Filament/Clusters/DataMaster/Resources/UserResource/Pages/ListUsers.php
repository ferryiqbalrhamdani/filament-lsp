<?php

namespace App\Filament\Clusters\DataMaster\Resources\UserResource\Pages;

use App\Filament\Clusters\DataMaster\Resources\UserResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListUsers extends ListRecords
{
    protected static string $resource = UserResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
