<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StationNotification extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function station()
    {
        return $this->hasOne(Station::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    protected $casts = [
        'id' => 'integer',
    ];
}
