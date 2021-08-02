<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Client extends Model
{
    protected $fillable = [
        'client_name',
        'address1',
        'address2',
        'city',
        'state',
        'country',
        'latitude',
        'longitude',
        'phone_no1',
        'phone_no2',
        'zip',
        'start_validity',
        'end_validity',
        'status',
    ];

    protected $casts = [
        'start_validity' => 'date',
        'end_validity' => 'date',
    ];

    public function user(): HasOne
    {
        return $this->hasOne(User::class);
    }
}
