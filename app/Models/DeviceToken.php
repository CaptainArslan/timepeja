<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class DeviceToken extends Model
{
    use HasFactory;

    protected $fillable = [
        'token',
        'device_type',
        'deviceable_id',
        'deviceable_type'
    ];


    // ----------------- Relationship -----------------
    public function deviceable(): MorphTo
    {
        return $this->morphTo();
    }
}
