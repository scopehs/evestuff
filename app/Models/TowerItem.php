<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TowerItem extends Model
{
    use HasFactory;

    protected $guarded = [];


    public function tower()
    {
        return $this->belongsTo(Tower::class);
    }

    public function item()
    {
        return $this->belongsTo(Item::class);
    }
}
