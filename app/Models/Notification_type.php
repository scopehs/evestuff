<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Notification_type extends Model
{
    protected $guarded = [];

    public function notifications()
    {
        return $this->hasMany(Notification::class);
    }

    protected $casts = [
        'id' => 'integer',
    ];
}
