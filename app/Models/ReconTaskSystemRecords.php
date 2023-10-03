<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReconTaskSystemRecords extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $casts = [
        'created_at' => 'datetime:Y-m-d H:i:s',
        'updated_at' => 'datetime:Y-m-d H:i:s',
        'id' => 'integer',
        'recon_task_id' => 'integer',
        'region_id' => 'integer',
        'system_id' => 'integer',
        'constellation_id' => 'integer',
        'user_id' => 'integer',

    ];
}
