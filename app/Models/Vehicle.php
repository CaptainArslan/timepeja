<?php

namespace App\Models;

use App\Traits\HasOrganization;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Vehicle extends Model
{
    use HasFactory;
    use SoftDeletes;
    use HasOrganization;

    protected $table = 'vehicles';

    public const STATUS_ACTIVE = true;
    public const STATUS_DEACTIVE = false;

    protected $fillable = [
        'vehicle_type_id',
        'organization_id',
        'number',
        'no_of_seat',
        'front_pic',
        'back_pic',
        'number_plate',
        'status',
    ];

    protected $casts = [
        'organization_id' => 'integer',
        'vehicle_type_id' => 'integer',
    ];

    protected $hidden = [
        'deleted_at',
    ];


    // -------------------------- Relations ---------------------------
    public function organization()
    {
        return $this->belongsTo(Organization::class);
    }

    public function vehicleType()
    {
        return $this->belongsTo(VehicleType::class);
    }

    public function location()
    {
        return $this->hasMany(Location::class);
    }

    public function trips()
    {
        return $this->hasMany(Trip::class, 'vehicle_id', 'id');
    }

    // ------------------ Accessors & Mutator -------------------------
    protected function status(): Attribute
    {
        return new Attribute(
            get: fn($value) => $value == self::STATUS_ACTIVE ? 'Active' : 'Deactive',
        );
    }

    // -------------------------- Scopes ------------------------------
    public function scopeActive(Builder $query): Builder
    {
        return $query->where('status', self::STATUS_ACTIVE);
    }

    public function scopeDeactive(Builder $query)
    {
        return $query->where('status', self::STATUS_DEACTIVE);
    }

    public function scopeSearch(Builder $query, $search = null): Builder
    {
        return $query->when($search, function ($q) use ($search) {
            $q->where('number', 'like', "%$search%")
                ->orWhere('number_plate', 'like', "%$search%")
                ->orWhere('no_of_seat', 'like', "%$search%")
                ->orWhereHas('vehicleType', function ($q) use ($search) {
                    $q->where('name', 'like', "%$search%");
                })
                ->orWhereHas('organization', function ($q) use ($search) {
                    $q->where('name', 'like', "%$search%");
                });
        });
    }
}
