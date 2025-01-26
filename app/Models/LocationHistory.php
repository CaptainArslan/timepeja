<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LocationHistory extends Model
{
    use HasFactory;

    protected $fillable = [
        'location',
        'passenger_id',
        'vehicle_id',
        'driver_id',
        'type',
    ];


    public function passenger()
    {
        return $this->belongsTo(Passenger::class);
    }

    public function vehicle()
    {
        return $this->belongsTo(Vehicle::class);
    }

    public function driver()
    {
        return $this->belongsTo(Driver::class);
    }

    // ----------------- SCOPES -----------------
    public function scopePassenger($query)
    {
        return $query->where('type', 'passenger');
    }

    public function scopeVehicle($query)
    {
        return $query->where('type', 'vehicle');
    }

    public function scopeDriver($query)
    {
        return $query->where('type', 'driver');
    }
}
