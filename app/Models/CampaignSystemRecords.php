<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CampaignSystemRecords extends Model
{
    use HasFactory;

    protected $casts = [
        'id' => 'integer',
        'campaigan_id' => 'integer',
        'system_id' => 'integer',
        'user_id' => 'integer',
        'site_id' => 'integer',
        'status_id' => 'integer',
        'user_attack' => 'integer',
    ];
}
