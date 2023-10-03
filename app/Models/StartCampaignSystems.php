<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StartCampaignSystems extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $casts = [
        'id' => 'integer',
        'start_campaign_id' => 'integer',
        'system_id' => 'integer',
        'constellation_id' => 'integer',
        'campaign_user_id' => 'integer',
        'campaign_system_status_id' => 'integer',
        'base_time' => 'integer',

    ];
}
