<?php

namespace App\Filament\Resources\CertificationListResource\Pages;

use Filament\Actions;
use Filament\Actions\Action;
use Filament\Resources\Pages\EditRecord;
use App\Filament\Resources\CertificationListResource;

class EditCertificationList extends EditRecord
{
    protected static string $resource = CertificationListResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }

    public function getHeaderTitle()
    {
        return '';
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        dd($data);
        return $data;
    }

    protected function getFormActions(): array
    {
        return [
            ...parent::getFormActions(),
        ];
    }
}
