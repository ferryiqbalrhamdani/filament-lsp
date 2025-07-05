<?php

namespace App\Filament\Resources\CertificationListResource\Pages;

use App\Filament\Resources\CertificationListResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\Auth;

class CreateCertificationList extends CreateRecord
{
    protected static string $resource = CertificationListResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['user_id'] = Auth::user()->id;
        return $data;
    }
}
