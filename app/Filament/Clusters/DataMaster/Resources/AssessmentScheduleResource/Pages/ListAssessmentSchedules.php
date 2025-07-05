<?php

namespace App\Filament\Clusters\DataMaster\Resources\AssessmentScheduleResource\Pages;

use App\Filament\Clusters\DataMaster\Resources\AssessmentScheduleResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListAssessmentSchedules extends ListRecords
{
    protected static string $resource = AssessmentScheduleResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
