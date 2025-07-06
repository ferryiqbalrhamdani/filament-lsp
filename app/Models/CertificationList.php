<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CertificationList extends Model
{
    protected $fillable = [
        'assessment_schedule_id'
    ];


    // Changed to BelongsTo since CertificationList contains the foreign key
    public function assessmentSchedule(): BelongsTo
    {
        return $this->belongsTo(AssessmentSchedule::class);
    }

    // Pada model CertificationList
    public function userCertification()
    {
        return $this->hasOne(UserCertification::class);
    }

    public function getUserCertification($userId)
    {
        return $this->userCertification()->where('user_id', $userId)->first();
    }

    public function getDocumentProgress($userId)
    {
        $userCert = $this->getUserCertification($userId);
        if (!$userCert) return null;

        $required = $this->assessmentSchedule->certificationScheme->documentTypes()->count();
        $uploaded = $userCert->documents()->count();

        return [
            'uploaded' => $uploaded,
            'required' => $required,
            'complete' => $uploaded >= $required
        ];
    }
}
