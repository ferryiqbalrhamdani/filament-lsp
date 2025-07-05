<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DigitalPayment extends Model
{
    protected $fillable = [
        'payment_method_id',
        'name',
        'nomor',
        'is_nomor',
    ];

    /**
     * Relasi ke PaymentMethod
     */
    public function paymentMethod()
    {
        return $this->belongsTo(PaymentMethod::class);
    }
}
