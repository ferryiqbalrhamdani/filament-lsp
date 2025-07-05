<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Unit extends Model
{
    protected $fillable = ['name', 'certification_scheme_id', 'nomor']; // di Unit

    public function certificationScheme(): BelongsTo
    {
        return $this->belongsTo(CertificationScheme::class);
    }

    public function elements(): HasMany
    {
        return $this->hasMany(Element::class);
    }
}
