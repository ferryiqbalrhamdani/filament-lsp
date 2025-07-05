<?php

namespace App\Filament\Resources\PaymentReviewResource\Pages;

use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use App\Filament\Resources\PaymentReviewResource;
use Filament\Pages\Concerns\ExposesTableToWidgets;

class ListPaymentReviews extends ListRecords
{
    use ExposesTableToWidgets;

    protected static string $resource = PaymentReviewResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }

    protected function getHeaderWidgets(): array
    {
        return PaymentReviewResource::getWidgets();
    }
}
