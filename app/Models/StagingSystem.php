<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StagingSystem extends Model
{
    use HasFactory;
    protected $guarded = [];


    public function system()
    {
        return $this->belongsTo(System::class);
    }

    public function dscan()
    {
        return $this->belongsTo(Dscan::class, 'system_id', 'system_id');
    }
}
