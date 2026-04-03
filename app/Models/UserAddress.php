<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserAddress extends Model
{
    protected $fillable = [
        'user_id', 'receiver_name', 'receiver_phone', 
        'province_code', 'ward_code', 'province_name', 'district_name', 'ward_name',
        'address_detail', 'full_address', 'is_default'
    ];

    protected $casts = [
        'is_default' => 'boolean'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
