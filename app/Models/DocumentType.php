<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DocumentType extends Model
{
    protected $fillable = [
        'name',
    ];

    public function certificationLists()
    {
        return $this->belongsToMany(CertificationList::class, 'certification_list_document_type')
            ->withTimestamps();
    }
}
