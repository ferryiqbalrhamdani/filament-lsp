<?php

namespace App\Filament\Resources\UserCertificationResource\Pages;

use App\Filament\Resources\UserCertificationResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListUserCertifications extends ListRecords
{
    protected static string $resource = UserCertificationResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
