<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NodeJoin extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function campaignSystem()
    {
        return $this->belongsTo(CampaignSystem::class);
    }

    public function campaignSystemMulti()
    {
        return $this->belongsTo(CampaignSystem::class, 'custom_campaign_id', ' custom_campaign_id');
    }

    public function campaignUser()
    {
        return $this->belongsTo(CampaignUser::class);
    }

    public function campaignStatus()
    {
        return $this->belongsTo(CampaignSystemStatus::class);
    }
}
