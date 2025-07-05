<?php

namespace App\Filament\Clusters\DataMaster\Resources\AssessmentScheduleResource\Pages;

use App\Filament\Clusters\DataMaster\Resources\AssessmentScheduleResource;
use App\Models\CertificationList;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateAssessmentSchedule extends CreateRecord
{
    protected static string $resource = AssessmentScheduleResource::class;

    protected function afterCreate(): void
    {
        $assessmentSchedule = $this->record;

        // Buat CertificationList baru
        $certificationList = CertificationList::create([
            'assessment_schedule_id' => $assessmentSchedule->id,
        ]);

        // // Ambil CertificationScheme dari AssessmentSchedule
        // $certificationScheme = $assessmentSchedule->certificationScheme;

        // // Ambil document types dari CertificationScheme
        // $documentTypeIds = $certificationScheme?->documentTypes?->pluck('id')->toArray() ?? [];

        // // Simpan ke pivot table CertificationList <-> DocumentType
        // $certificationList->documentTypes()->sync($documentTypeIds);
    }
}
