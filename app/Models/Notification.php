<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    protected $guarded = [];

    public function system()
    {
        return $this->belongsTo(System::class);
    }

    public function item()
    {
        return $this->belongsTo(Item::class);
    }

    public function notification_type()
    {
        return $this->belongsTo(Notification_type::class);
    }

    public function status()
    {
        return $this->belongsTo(Status::class);
    }

    public function dscan()
    {
        return $this->belongsTo(Dscan::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    protected $casts = [
        'id' => 'integer',
        'system_id' => 'integer',
        'item_id' => 'integer',
        'notification_type_id' => 'integer',
        'si_id' => 'integer',
    ];
}
