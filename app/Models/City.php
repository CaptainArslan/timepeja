<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class City extends Model
{
    use HasFactory;

    protected $table = 'cities';

    protected $fillable = [
        'id',
        'name',
        's_id',
        'latitude',
        'longitude',
        'wikiDataId',
        'flag',
    ];

    protected $casts = [
        's_id' => 'integer',
    ];


    public function state()
    {
        return $this->belongsTo(State::class, 's_id');
    }
}
