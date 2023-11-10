<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FavoriteRoute extends Model
{
    use HasFactory;
    protected $table = 'favourite_routes';

    public function route()
    {
        return $this->belongsTo(Route::class, 'route_id', 'id');
    }

    public function passenger()
    {
        return $this->belongsTo(Passenger::class, 'passenger_id', 'id');
    }
}
