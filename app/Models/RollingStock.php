<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RollingStock extends Model
{
    protected $fillable =[
        'code',
        'type',
        'seats',
        'series',

    ];

    public function convoys()
    {
        return $this->belongsToMany(Convoy::class)
                    ->withPivot('position');
    }
}
