<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class AssessmentSchedule extends Model
{
    protected $fillable = [
        'certification_scheme_id',
        'competency_unit_location_id',
        'tgl_mulai',
        'tgl_selesai',
        'tgl_publish',
        'tgl_tutup_publish',
        'is_active',
    ];

    public function certificationScheme(): BelongsTo
    {
        return $this->belongsTo(CertificationScheme::class);
    }

    public function competencyUnitLocation(): BelongsTo
    {
        return $this->belongsTo(CompetencyUnitLocation::class);
    }

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class);
    }

    public function certificationList(): HasOne
    {
        return $this->hasOne(CertificationList::class);
    }
}
