<?php

namespace App\Models;

use App\Models\Organization;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Location extends Model
{
    use HasFactory;

    protected $fillable = [
        'id', 'user_id', 'organization_id', 'name', 'passenger_id', 'vehicle_id', 'driver_id', 'type', 'latitude', 'longitude',
    ];

    /**
     * get the request organization
     *
     * @return void
     */
    public function organization()
    {
        return $this->belongsTo(Organization::class);
    }
}
