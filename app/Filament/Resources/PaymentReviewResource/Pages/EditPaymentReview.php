<?php

namespace App\Filament\Resources\PaymentReviewResource\Pages;

use App\Filament\Resources\PaymentReviewResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditPaymentReview extends EditRecord
{
    protected static string $resource = PaymentReviewResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }

    protected function beforeSave(): void
    {
        if ($this->data['is_verified'] == true) {
            $this->record->keterangan = 'Pembayaran telah diverifikasi';
        } else if ($this->data['is_verified'] == false) {
            $this->record->keterangan = 'Pembayaran tidak memenuhi syarat';
        }

        // dd($this->record->is_verified, $this->record->keterangan, $this->data);
    }

    protected function afterSave(): void
    {
        $record = $this->record;
        if ($record->is_verified == true) {
            $record->payment->userCertification->update([
                'status' => 'verified',
            ]);
        } else if ($record->is_verified == false) {
            $record->payment->userCertification->update([
                'status' => 'payment_failed',
            ]);
        }
    }

    protected function getRedirectUrl(): string
    {
        return $this->previousUrl ?? $this->getResource()::getUrl('index');
    }
}
