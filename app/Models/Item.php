<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    protected $guarded = [];
    public $timestamps = false;

    public function stations()
    {
        return $this->hasMany(Station::class);
    }

    public function chillStations()
    {
        return $this->hasMany(ChillStation::class);
    }

    public function group()
    {
        return $this->belongsTo(Group::class);
    }

    protected $casts = [
        'id' => 'integer',
    ];
}
