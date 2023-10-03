<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class NotificationRecords extends Model
{
    protected $guarded = [];

    public $timestamps = false;

    protected $casts = [
        'id' => 'integer',
        'system_id' => 'integer',
        'region_id' => 'integer',
        'item_id' => 'integer',
        'adm' => 'double',
        'notification_type_id' => 'integer',
        'status_id' => 'integer',
    ];
}
