<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AplOne extends Model
{
    protected $fillable = [
        'user_id',
        'payment_review_id',
        'status',
    ];


    public function paymentReview()
    {
        return $this->belongsTo(PaymentReview::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
