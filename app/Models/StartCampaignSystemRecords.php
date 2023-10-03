<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StartCampaignSystemRecords extends Model
{
    use HasFactory;

    protected $casts = [
        'constellation_id' => 'integer',
        'id' => 'integer',
        'start_campaign_id' => 'integer',
        'site_id' => 'integer',
        'start_campaign_id' => 'integer',
        'status_id' => 'integer',
        'system_id' => 'integer',
        'user_id' => 'integer',
    ];
}
