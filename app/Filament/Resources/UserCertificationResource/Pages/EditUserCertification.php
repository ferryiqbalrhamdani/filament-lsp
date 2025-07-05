<?php

namespace App\Filament\Resources\UserCertificationResource\Pages;

use App\Filament\Resources\UserCertificationResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

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

    protected function getFormActions(): array
    {
        return [];
    }
}
