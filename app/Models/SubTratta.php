<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class SubTratta extends Model
{
    use HasFactory;

    protected $fillable = [
        'train_id',
        'from_station_id',
        'to_station_id',
        'departure_time',
        'arrival_time',
        'direction',
        'order',
    ];

    public function train()
    {
        return $this->belongsTo(Train::class);
    }

    public function fromStation()
    {
        return $this->belongsTo(Station::class, 'from_station_id');
    }

    public function toStation()
    {
        return $this->belongsTo(Station::class, 'to_station_id');
    }
}