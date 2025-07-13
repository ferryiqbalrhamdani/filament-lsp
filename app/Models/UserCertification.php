<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserCertification extends Model
{
    protected $fillable = [
        'user_id',
        'certification_list_id',
        'status',
        'payment_method',
        'payment_proof'
    ];

    protected $casts = [
        'custom_fields' => 'array',
    ];


    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function certificationList()
    {
        return $this->belongsTo(CertificationList::class);
    }

    public function documents()
    {
        return $this->hasMany(CertificationDocument::class);
    }

    public function payment()
    {
        return $this->hasOne(Payment::class);
    }
}
