<?php

namespace App\Models;

use App\Models\Route;
use App\Models\Driver;
use App\Models\Manager;
use App\Models\Vehicle;
use App\Models\Schedule;
use App\Models\Passenger;
use App\Models\OrganizationType;
use App\Models\Request;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Casts\Attribute;

class Organization extends Model
{
    use HasFactory;
    use SoftDeletes;

    public const STATUS_ACTIVE = 1;
    public const STATUS_DEACTIVE = 0;

    protected $fillable = [
        'organization_type_id',
        'name',
        'branch_name',
        'branch_code',
        'email',
        'phone',
        'code',
        'state_id',
        'city_id',
        'address',
        'head_name',
        'head_email',
        'head_phone',
        'head_address',
        'status',
        'deactivate_code',
    ];

    protected $hidden = [
        'deactivate_code',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $casts = [
        'state_id' => 'integer',
        'city_id' => 'integer',
        'organization_type_id' => 'integer',
    ];

    protected $appends = [
        'full_address',
        'full_name'
    ];

    // ------------------ Relationships --------------------------------
    public function manager(): HasOne
    {
        return $this->hasOne(Manager::class);
    }

    public function organizationType(): BelongsTo
    {
        return $this->belongsTo(OrganizationType::class);
    }

    public function passengers(): HasMany
    {
        return $this->hasMany(Passenger::class, 'o_id', 'id');
    }

    public function drivers(): HasMany
    {
        return $this->hasMany(Driver::class);
    }

    public function vehicles(): HasMany
    {
        return $this->hasMany(Vehicle::class, 'o_id', 'id');
    }

    public function routes(): HasMany
    {
        return $this->hasMany(Route::class);
    }

    public function schedules(): HasMany
    {
        return $this->hasMany(Schedule::class, 'o_id', 'id');
    }

    public function requests(): HasMany
    {
        return $this->hasMany(Request::class);
    }

    public function locations(): HasMany
    {
        return $this->hasMany(Location::class);
    }


    // ------------------ Accessors & Mutator --------------------------------
    protected function name(): Attribute
    {
        return new Attribute(
            get: fn($value) => strtolower($value),
            set: fn($value) => ucwords(strtolower($value))
        );
    }

    protected function address(): Attribute
    {
        return new Attribute(
            get: fn($value) => json_decode($value, true),
            set: fn($value) => json_encode($value)
        );
    }

    protected function headAddress(): Attribute
    {
        return new Attribute(
            get: fn($value) => json_decode($value, true),
            set: fn($value) => json_encode($value)
        );
    }

    protected function status(): Attribute
    {
        return new Attribute(
            get: fn($value) => $value == self::STATUS_ACTIVE ? 'Active' : 'Deactive',
        );
    }

    protected function fullName(): Attribute
    {
        return new Attribute(
            get: function () {
                $branchCode = $this->branch_code ?? '';
                $name = $this->name ?? '';
                $branchName = $this->branch_name ?? '';
                $city = $this->address['city'] ?? '';

                return trim("$branchCode - $name, $branchName, $city", ' ,');
            }
        );
    }

    protected function fullAddress(): Attribute
    {
        return new Attribute(
            get: function () {
                $address = $this->address['address'] ?? '';
                $city = $this->address['city'] ?? '';
                $state = $this->address['state'] ?? '';

                return trim("$address, $city, $state", ' ,');
            }
        );
    }

    // ------------------ Custom Functions --------------------------------
    public function isActive(): bool
    {
        return $this->status === self::STATUS_ACTIVE;
    }
}
