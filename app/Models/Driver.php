<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Driver extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'o_id',
        'u_id',
        'name',
        'email',
        'password',
        'phone',
        'cnic',
        'cnic_front_pic',
        'cnic_back_pic',
        'cnic_expiry_date',
        'license_no',
        'license_no_front_pic',
        'license_no_back_pic',
        'license_expiry_date',
        'otp',
        'token',
        'status',
        'online_status',
    ];

    public function organization()
    {
        return $this->belongsTo(Organization::class, 'o_id', 'id');
    }

    // ----------------------------------------------------------------
    // ------------------ Accessors & Mutator -------------------------
    // ----------------------------------------------------------------

    /**
     * Get the front picture of the cnic.
     *
     * @param  string  $value
     * @return string|null
     */
    public function getCnicFrontPicAttribute()
    {
        return $this->attributes['cnic_front_pic'] ? asset('uploads/drivers/cnic/' . $this->attributes['cnic_front_pic']) : null;
    }

    /**
     * Get the back picture of the cnic.
     *
     * @param  string  $value
     * @return string|null
     */
    public function getCnicBackPicAttribute()
    {
        return $this->attributes['cnic_back_pic'] ? asset('uploads/drivers/cnic/' . $this->attributes['cnic_back_pic']) : null;
    }

    /**
     * Get the front picture of the cnic.
     *
     * @param  string  $value
     * @return string|null
     */
    public function getLicenseNoFrontPicAttribute()
    {
        return $this->attributes['license_no_front_pic'] ? asset('uploads/drivers/license/' . $this->attributes['license_no_front_pic']) : null;
    }

    /**
     * Get the back picture of the cnic.
     *
     * @param  string  $value
     * @return string|null
     */
    public function getLicenseNoBackPicAttribute()
    {
        return $this->attributes['license_no_back_pic'] ? asset('uploads/drivers/license/' . $this->attributes['license_no_back_pic']) : null;
    }
}
