<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CertificationScheme extends Model
{
    protected $fillable = [
        'nomor_skema',
        'judul_skema',
        'jenis_skema',
        'deskripsi_skema',
        'tujuan_skema',
        'kode_referensi',
        'tahun_terbit',
        'lembaga_penyelenggara',
        'harga',
        'is_active_skema',
    ];

    public function documentTypes(): BelongsToMany
    {
        return $this->belongsToMany(DocumentType::class, 'certification_scheme_document_type', 'certification_id', 'document_type_id');
    }

    public function units(): HasMany
    {
        return $this->hasMany(Unit::class);
    }
}
