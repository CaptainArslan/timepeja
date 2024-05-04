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

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function vehicle()
    {
        return $this->belongsTo(Vehicle::class);
    }

    public function driver()
    {
        return $this->belongsTo(Driver::class);
    }

    public function passenger()
    {
        return $this->belongsTo(Passenger::class);
    }

    // ===================== SCOPES =====================
    public function scopeDriverLocation($query, $id)
    {
        return $query->where('vehicle_id', $id)->where('type', 'driver')->latest()->first();
    }

    public function scopePassengerLocation($query, $id)
    {
        return $query->where('passenger_id', $id)->where('type', 'passenger')->latest()->first();
    }

    public function scopeVehicleLocation($query, $id)
    {
        return $query->where('vehicle_id', $id)->where('type', 'driver')->latest()->first();
    }

    public function scopePassengerVehicleLocation($query, $id)
    {
        return $query->where('vehicle_id', $id)->where('type', 'passenger')->latest()->first();
    }

    public function scopePassengerDriverLocation($query, $id)
    {
        return $query->where('driver_id', $id)->where('type', 'passenger')->latest()->first();
    }

}
