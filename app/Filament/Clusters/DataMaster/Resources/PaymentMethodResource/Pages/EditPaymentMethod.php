<?php

namespace App\Filament\Clusters\DataMaster\Resources\PaymentMethodResource\Pages;

use App\Filament\Clusters\DataMaster\Resources\PaymentMethodResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditPaymentMethod extends EditRecord
{
    protected static string $resource = PaymentMethodResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
