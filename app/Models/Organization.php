<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Organization extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'name',
        'branch_name',
        'email',
        'phone',
        's_id',
        'c_id',
        'address',
        'head_name',
        'head_email',
        'head_phone',
        'head_address',
        'status'
    ];

    /**
     * manager relation function with manager
     *
     * @return void
     */
    public function manager()
    {
        return $this->belongsTo(Manager::class, 'id', 'o_id');
    }

    /**
     * city relation with organization
     *
     * @return  [relation]  [this function will return city that belogs to organizations]
     */
    public function city()
    {
        return $this->belongsTo(City::class, 'c_id', 'id');
    }

    /**
     * state relation with organization
     *
     * @return  [type]  [this function will return state that belogs to organizations]
     */
    public function state()
    {
        return $this->belongsTo(State::class, 's_id', 'id');
    }

    /**
     * organization type relation with organization
     *
     * @return  [type]  [this function will return organization type that belogs to organizations]
     */
    public function organizationType()
    {
        return $this->belongsTo(OrganizationType::class, 'o_type_id', 'id');
    }
}
