<?php

namespace App\Filament\Clusters\Sertifikasi\Resources\AplOneResource\Pages;

use Filament\Actions;
use Illuminate\Support\Facades\Auth;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\EditRecord;
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
                ->outlined(),
        ];
    }

    protected function beforeSave(): void
    {
        $data = $this->data;

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
        if ($this->record->status == 'pending') {
            $this->record->update([
                'user_id' => NULL
            ]);
        } else {
            $this->record->update([
                'user_id' => Auth::user()->id
            ]);
        }
    }

    protected function getRedirectUrl(): string
    {
        return $this->previousUrl ?? $this->getResource()::getUrl('index');
    }
}
