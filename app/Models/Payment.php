<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    protected $fillable = [
        'user_certification_id',
        'payment_method_id',
        'amount',
        'bukti_pembayaran',
        'keterangan',
        'digital_payment',
        'digital_payment_nomor',
        'status',
    ];

    /**
     * Relasi ke PaymentMethod
     */
    public function paymentMethod()
    {
        return $this->belongsTo(PaymentMethod::class);
    }

    /**
     * Relasi ke UserCertification
     */
    public function userCertification()
    {
        return $this->belongsTo(UserCertification::class);
    }

    /**
     * Relasi ke PaymentReview
     */
    public function review()
    {
        return $this->hasOne(PaymentReview::class);
    }
}
