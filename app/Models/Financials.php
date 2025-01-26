<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Financials extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'financials';

    protected $fillable = [
        'organization_id',
        'org_wallet',
        'org_payment',
        'org_amount',
        'org_trail_start_date',
        'org_trail_end_date',
        'driver_wallet',
        'driver_payment',
        'driver_amount',
        'driver_trail_start_date',
        'driver_trail_end_date',
        'passenger_wallet',
        'passenger_payment',
        'passenger_amount',
        'passenger_trail_start_date',
        'passenger_trail_end_date'
    ];

    protected $casts = [
        'organization_id' => 'integer',
    ];


    public function organization() : BelongsTo
    {
        return $this->belongsTo(Organization::class);
    }

}
