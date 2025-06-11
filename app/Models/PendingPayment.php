<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PendingPayment extends Model
{
    protected $fillable = [
        'transaction_id',
        'user_id',
        'train_id',
        'from_station_id',
        'to_station_id',
        'posti',
        'cost',
        'name',
        'surname',
    ];

    protected $casts = [
        'posti' => 'array',
    ];

    public $timestamps = true;
}
