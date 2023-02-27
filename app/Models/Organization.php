<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\TransportManager;

class Organization extends Model
{
    use HasFactory, SoftDeletes;
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
        'status',
        'created_at',
        'updated_at',
        'deleted_at'
    ];

    /**
     * manager relation function with manager
     *
     * @return void
     */
    public function manager()
    {
        return $this->hasOne(TransportManager::class, 'org_id', 'id');
    }
}
