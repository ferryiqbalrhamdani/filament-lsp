<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PaymentReview extends Model
{
    protected $fillable = [
        'payment_id',
        'is_verified',
        'keterangan',
    ];

    public function payment()
    {
        return $this->belongsTo(Payment::class);
    }

    public function aplOne()
    {
        return $this->hasOne(AplOne::class);
    }
}
