<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Organization extends Model
{
    use HasFactory;
    use SoftDeletes;

    public const STATUS_ACTIVE = 1;
    public const STATUS_DEACTIVE = 0;

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



    // ----------------------------------------------------------------
    // ------------------ Accessors & Mutator -------------------------
    // ----------------------------------------------------------------

    /**
     * Set the name attribute.
     *
     * @param  string  $value
     * @return void
     */
    public function setNameAttribute($value)
    {
        $this->attributes['name'] = ucwords(strtolower($value));
    }

    /**
     * Get the name attribute.
     *
     * @param  string  $value
     * @return string
     */
    public function getNameAttribute($value)
    {
        return ucwords(strtolower($value));
    }


    /**
     * Set the phone number attribute.
     *
     * @param  string  $value
     * @return void
     */
    public function setPhoneAttribute($value)
    {
        $this->attributes['phone'] = str_replace('-', '', $value);
    }

    /**
     * Get the phone number attribute.
     *
     * @param  string  $value
     * @return string
     */
    public function getPhoneAttribute($value)
    {
        return substr($value, 0, 4) . '-' . substr($value, 4, 8);
    }

    /**
     * Set the head phone attribute.
     *
     * @param  string  $value
     * @return void
     */
    public function setHeadPhoneAttribute($value)
    {
        $this->attributes['head_phone'] = str_replace('-', '', $value);
    }

    /**
     * Get the head phone attribute.
     *
     * @param  string  $value
     * @return string
     */
    public function getHeadPhoneAttribute($value)
    {
        return substr($value, 0, 4) . '-' . substr($value, 7);
    }

    /**
     * Set the org head name attribute.
     *
     * @param  string  $value
     * @return void
     */
    public function setOrgHeadPhoneAttribute($value)
    {
        $this->attributes['head_phone'] = ucwords(strtolower($value));
    }

    /**
     * Get the org head name attribute.
     *
     * @param  string  $value
     * @return string
     */
    public function getOrgHeadPhoneAttribute($value)
    {
        return ucwords(strtolower($this->attributes['head_phone']));
    }
}
