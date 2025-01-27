<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Otp extends Model
{
    use HasFactory;

    protected $table = 'otps';

    protected $fillable = [
        'otp',
        'phone',
        'is_verified',
        'expires_at',
        'verified_at',
    ];

    protected $casts = [
        'expires_at' => 'datetime',
        'verified_at' => 'datetime',
    ];


    // ------------------ Scopes --------------------------------
    public function scopeByActive($query)
    {
        return $query->where('expires_at', '>', now());
    }

    // ------------------ Custom Functions ----------------------
    public function isActive(): bool
    {
        return $this->expires_at > now();
    }
}
