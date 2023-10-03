<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CampaignSystem extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function campaignstatus()
    {
        return $this->belongsTo(CampaignSystemStatus::class);
    }

    public function campaign()
    {
        return $this->belongsTo(Campaign::class);
    }

    public function campaignusers()
    {
        return $this->hasMany(CampaignUser::class);
    }

    public function system()
    {
        return $this->belongsTo(System::class);
    }

    public function nodeJoin()
    {
        return $this->hasMany(NodeJoin::class);
    }

    protected $casts = [
        'id' => 'integer',
        'campaigan_id' => 'integer',
        'system_id' => 'integer',
        'campaigan_user_id' => 'integer',
        'campaigan_system_status_id' => 'integer',
    ];
}
