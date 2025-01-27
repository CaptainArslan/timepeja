<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;

class VehicleType extends Model
{
    use HasFactory;
    use SoftDeletes;


    protected $table = 'vehicle_types';
    public const STATUS_ACTIVE = true;
    public const STATUS_INACTIVE = false;

    protected $fillable = [
        'name',
        'description',
        'status',
    ];

    protected $casts = [
        'status' => 'boolean',
    ];


    protected $hidden = [
        'deleted_at'
    ];


    // ------------------- Relationships --------------------------------
    public function vehicles(): HasMany
    {
        return $this->hasMany(Vehicle::class);
    }

    // ------------------ Accessors & Mutator -------------------------
    protected function name(): Attribute
    {
        return new Attribute(
            get: fn($value) => ucwords(strtolower($value)),
            set: fn($value) => strtolower($value),
        );
    }
}
