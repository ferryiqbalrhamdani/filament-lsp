<?php

namespace App\Filament\Resources\PaymentReviewResource\Pages;

use Filament\Actions;
use App\Models\AplOne;
use Filament\Resources\Pages\EditRecord;
use App\Filament\Resources\PaymentReviewResource;

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

        // dd($record->is_verified, $record->aplOne);

        // if (!$record->aplOne && $record->is_verified == 1) {
        //     $record->aplOne()->create([
        //         'payment_review_id' => $record->id
        //     ]);
        // }


        if ($record->is_verified == true) {
            $record->payment->userCertification->update([
                'status' => 'verified',
            ]);
            if (!$record->aplOne) {
                $record->aplOne()->create([
                    'payment_review_id' => $record->id
                ]);
            }
        } else if ($record->is_verified == false) {
            $record->payment->userCertification->update([
                'status' => 'payment_failed',
            ]);
            if ($record->aplOne) {
                $record->aplOne->delete();
            }
        }
    }

    protected function getRedirectUrl(): string
    {
        return $this->previousUrl ?? $this->getResource()::getUrl('index');
    }
}
