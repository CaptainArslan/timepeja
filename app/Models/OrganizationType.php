<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class OrganizationType extends Model
{
    use HasFactory;
    use SoftDeletes;

    /**
     * array for fillable
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'desc',
        'status'
    ];


    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        // 
    ];

    public function organizations() : HasMany
    {
        return $this->hasMany(Organization::class);
    }
}
