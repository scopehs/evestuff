<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Structure extends Model
{
    protected $guarded = [];

    public function system()
    {
        return $this->belongsTo(System::class);
    }

    public function campaign()
    {
        return $this->hasOne(Campaign::class);
    }

    protected $casts = [
        'id' => 'integer',
        'alliance_id' => 'integer',
        'system_id' => 'integer',
        'item_id' => 'integer',
        'adm' => 'double',
        'status' => 'integer',
    ];
}
