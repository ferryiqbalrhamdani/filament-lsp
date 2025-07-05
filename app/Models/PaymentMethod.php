<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PaymentMethod extends Model
{
    protected $fillable = ['name'];

    public function digitalPayments()
    {
        return $this->hasMany(DigitalPayment::class);
    }

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }
}
