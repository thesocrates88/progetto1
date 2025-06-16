<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Train extends Model
{
    protected $fillable = [
        'convoy_id',
        'name',
        'departure_station_id',
        'arrival_station_id',
        'date',
        'departure_time',
        'arrival_time',
    ];

    public function convoy()
    {
        return $this->belongsTo(Convoy::class);
    }

    public function departureStation()
    {
        return $this->belongsTo(Station::class, 'departure_station_id');
    }

    public function arrivalStation()
    {
        return $this->belongsTo(Station::class, 'arrival_station_id');
    }

    public function subtratte()
    {
        return $this->hasMany(\App\Models\SubTratta::class);
    }

    public function tickets()
    {
        return $this->hasMany(Ticket::class);
    }
}
