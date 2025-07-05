<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Element extends Model
{
    protected $fillable = ['name', 'kriteria', 'unit_id', 'content', 'status', 'keterangan']; // di Element

    public function unit(): BelongsTo
    {
        return $this->belongsTo(Unit::class);
    }
}
