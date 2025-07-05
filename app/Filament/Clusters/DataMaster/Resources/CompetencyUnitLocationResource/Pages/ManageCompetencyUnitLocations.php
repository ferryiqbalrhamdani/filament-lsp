<?php

namespace App\Filament\Clusters\DataMaster\Resources\CompetencyUnitLocationResource\Pages;

use Filament\Actions;
use Filament\Support\Enums\MaxWidth;
use Filament\Resources\Pages\ManageRecords;
use App\Filament\Clusters\DataMaster\Resources\CompetencyUnitLocationResource;

class ManageCompetencyUnitLocations extends ManageRecords
{
    protected static string $resource = CompetencyUnitLocationResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->modalWidth(MaxWidth::Medium),
        ];
    }
}
