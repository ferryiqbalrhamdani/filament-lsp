<?php

namespace App\Filament\Resources\UserCertificationResource\Pages;

use Filament\Actions;
use App\Models\UserCertification;
use Illuminate\Support\Facades\Crypt;
use Filament\Resources\Pages\EditRecord;
use App\Filament\Resources\UserCertificationResource;
use Illuminate\Contracts\Encryption\DecryptException;

class EditUserCertification extends EditRecord
{
    protected static string $resource = UserCertificationResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('back')
                ->label('Kembali')
                ->icon('heroicon-s-arrow-left')
                ->outlined()
                ->url(UserCertificationResource::getUrl('index')),
        ];
    }

    protected function resolveRecord($key): UserCertification
    {
        try {
            $realId = Crypt::decrypt($key);
        } catch (DecryptException $e) {
            abort(404);
        }

        return UserCertification::findOrFail($realId);
    }

    protected function getFormActions(): array
    {
        return [];
    }
}
