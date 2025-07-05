<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CertificationDocument extends Model
{
    protected $fillable = [
        'user_certification_id', // ID pendaftaran user
        'document_type_id',      // ID jenis dokumen
        'file_path'             // Path/lokasi penyimpanan file
    ];

    /**
     * Relasi ke UserCertification
     */
    public function userCertification(): BelongsTo
    {
        return $this->belongsTo(UserCertification::class);
    }

    /**
     * Relasi ke DocumentType
     */
    public function documentType(): BelongsTo
    {
        return $this->belongsTo(DocumentType::class);
    }

    /**
     * Accessor untuk URL dokumen
     */
    public function getFileUrlAttribute(): string
    {
        return asset('storage/' . $this->file_path);
    }

    /**
     * Accessor untuk nama file
     */
    public function getFileNameAttribute(): string
    {
        return basename($this->file_path);
    }

    /**
     * Scope untuk dokumen dari user tertentu
     */
    public function scopeForUser($query, $userId)
    {
        return $query->whereHas('userCertification', function ($q) use ($userId) {
            $q->where('user_id', $userId);
        });
    }
}
