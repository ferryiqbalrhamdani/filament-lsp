<?php

namespace App\Filament\Clusters\Sertifikasi\Resources\AplOneResource\Pages;

use Filament\Actions;
use App\Models\AplOne;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Contracts\Encryption\DecryptException;
use App\Filament\Clusters\Sertifikasi\Resources\AplOneResource;

class EditAplOne extends EditRecord
{
    protected static string $resource = AplOneResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
            Actions\Action::make('print')
                ->label('Print')
                ->icon('heroicon-s-printer')
                ->outlined()
                ->url(route('print.apl-one', ['id' => Crypt::encrypt($this->getRecord()->id)]))
                ->openUrlInNewTab()
        ];
    }

    protected function resolveRecord($key): AplOne
    {
        try {
            $realId = Crypt::decrypt($key);
        } catch (DecryptException $e) {
            abort(404);
        }

        return AplOne::findOrFail($realId);
    }

    protected function beforeSave(): void
    {
        $data = $this->data;

        // dd($this->getRecord()->id);

        // dd($data['user_id']);

        if (! $this->getRecord()->user) {
            $data['user_id'] = Auth::user()->id;
        } else {
            if ($data['user_id'] != Auth::user()->id) {
                Notification::make()
                    ->warning()
                    ->title('Anda tidak dapat mengubah data ini')
                    ->body('Anda tidak memiliki hak akses untuk mengubah data ini karena data ini sudah diubah oleh Asesor: ' . $this->getRecord()->user->name)
                    ->send();

                $this->halt();
            } else {
                $this->data['user_id'] = Auth::user()->id;
            }
        }
    }


    protected function afterSave(): void
    {
        $status = $this->record->status;
        $userId = Auth::id();

        if ($status === 'pending') {
            $this->record->user_id = null;
            $this->record->aplTwo?->delete();
        } elseif ($status === 'failed') {
            $this->record->user_id = $userId;
            $this->record->aplTwo?->delete();
        } elseif ($status === 'verified') {
            $this->record->user_id = $userId;
            $this->record->aplTwo()->create();
        } else {
            $this->record->user_id = null;
        }

        $this->record->save();
    }


    protected function getRedirectUrl(): string
    {
        return $this->previousUrl ?? $this->getResource()::getUrl('index');
    }
}
