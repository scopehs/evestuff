<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DscanLocal extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function dscan()
    {
        return $this->belongsTo(Dscan::class);
    }
}
