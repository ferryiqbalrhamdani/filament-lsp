<?php

namespace App\Filament\Clusters\DataMaster\Resources\UserResource\Pages;

use App\Filament\Clusters\DataMaster\Resources\UserResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateUser extends CreateRecord
{
    protected static string $resource = UserResource::class;
}
