<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Region extends Model
{
    protected $guarded = [];

    public function systems()
    {
        return $this->hasMany(System::class);
    }

    public function constellations()
    {
        return $this->hasMany(Constellation::class);
    }

    public function moons()
    {
        return $this->hasMany(Moon::class);
    }

    protected $casts = [
        'id' => 'integer',
    ];
}
