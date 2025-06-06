<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Convoy extends Model
{
    protected $fillable = ['name'];

    public function rollingStocks()
    {
        return $this->belongsToMany(RollingStock::class)
                    ->withPivot('position')
                    ->orderBy('convoy_rolling_stock.position');
    }

    public function trains()
    {
        return $this->hasMany(Train::class);
    }

}