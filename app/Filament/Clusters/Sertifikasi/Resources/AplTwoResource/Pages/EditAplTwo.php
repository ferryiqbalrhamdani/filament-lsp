<?php

namespace App\Filament\Clusters\Sertifikasi\Resources\AplTwoResource\Pages;

use Filament\Actions;
use App\Models\AplTwo;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Contracts\Encryption\DecryptException;
use App\Filament\Clusters\Sertifikasi\Resources\AplTwoResource;

class EditAplTwo extends EditRecord
{
    protected static string $resource = AplTwoResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }

    protected function resolveRecord($key): AplTwo
    {
        try {
            $realId = Crypt::decrypt($key);
        } catch (DecryptException $e) {
            abort(404);
        }

        return AplTwo::findOrFail($realId);
    }

    protected function beforeSave(): void
    {
        // dd($this->data);
    }

    protected function getFormActions(): array
    {
        $actions = [];

        if (! Auth::user()->hasRole('asesi')) {
            $actions[] = parent::getSaveFormAction();
            $actions[] = parent::getCancelFormAction();
        }

        return $actions;
    }
}
