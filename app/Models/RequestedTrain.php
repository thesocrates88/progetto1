<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RequestedTrain extends Model
{
    use HasFactory;

    protected $fillable = [
        'departure_station_id',
        'arrival_station_id',
        'date',
        'departure_time',
        'seats',
        'admin_message',
        'exercise_message',
        'status',
        'created_by',
        'train_id',
    ];

    public function departureStation()
    {
        return $this->belongsTo(Station::class, 'departure_station_id');
    }

    public function arrivalStation()
    {
        return $this->belongsTo(Station::class, 'arrival_station_id');
    }

    public function train()
    {
        return $this->belongsTo(Train::class);
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
