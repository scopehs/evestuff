<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TimersRecord extends Model
{
    protected $table = 'timer_records';

    public function alliance()
    {
        return $this->belongsTo(Alliance::class);
    }

    protected $casts = [
        'region_id' => 'integer',
        'constellation_id' => 'integer',
        'system_id' => 'integer',
        'alliance_id' => 'integer',
        'region_id' => 'integer',
        'standing' => 'double',
        'color' => 'integer',
        'adm' => 'double',
        'status' => 'integer',
        'id' => 'integer',
    ];
}
