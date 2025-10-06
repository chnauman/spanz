<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CompanyDetail extends Model
{
    protected $fillable = [
        'user_id',
        'company_name',
        'abn',
        'registration_number',
        'address',
        'city',
        'state',
        'postal_code',
        'country',
        'phone',
        'website',
        'description',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
