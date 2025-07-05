<?php

namespace App\Filament\Clusters\DataMaster\Resources\DigitalPaymentResource\Pages;

use App\Filament\Clusters\DataMaster\Resources\DigitalPaymentResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditDigitalPayment extends EditRecord
{
    protected static string $resource = DigitalPaymentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
