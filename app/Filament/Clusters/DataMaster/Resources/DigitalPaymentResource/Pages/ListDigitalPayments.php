<?php

namespace App\Filament\Clusters\DataMaster\Resources\DigitalPaymentResource\Pages;

use App\Filament\Clusters\DataMaster\Resources\DigitalPaymentResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListDigitalPayments extends ListRecords
{
    protected static string $resource = DigitalPaymentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
