<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AplTwo extends Model
{
    protected $fillable = [
        'apl_one_id',
        'user_id',
        'no_reg',
    ];

    public function aplOne()
    {
        return $this->belongsTo(AplOne::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
