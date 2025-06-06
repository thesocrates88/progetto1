<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ticket extends Model
{
    protected $fillable = [
        'user_id',
        'train_id',
        'departure_station_id',
        'arrival_station_id',
        'departure_time',
        'arrival_time',
        'costo',
        'rolling_stock_id',
        'numero_posto',
        'payed_at',
        'payment_token',
    ];

    //relazioni per il metodo ticket@index
    public function train() {
    return $this->belongsTo(Train::class);
    }
    public function departureStation() {
        return $this->belongsTo(Station::class, 'departure_station_id');
    }
    public function arrivalStation() {
        return $this->belongsTo(Station::class, 'arrival_station_id');
    }
    public function rollingStock() {
        return $this->belongsTo(RollingStock::class);
    }

}
