<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CampaignUser extends Model
{
    use HasFactory;

    protected $guarded = [];

    public $timestamps = false;

    protected $casts = [
        'id' => 'integer',
        'site_id' => 'integer',
        'campaign_id' => 'integer',
        'campaign_system_id' => 'integer',
        'link' => 'string',
        'system_id' => 'integer',
        'status_id' => 'integer',
        'campaign_role_id' => 'integer',

    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function campaignsystem()
    {
        return $this->belongsTo(CampaignSystem::class);
    }

    public function system()
    {
        return $this->belongsTo(System::class);
    }

    public function campaign()
    {
        return $this->belongsTo(Campaign::class);
    }

    public function status()
    {
        return $this->belongsTo(CampaignUserStatus::class);
    }

    public function role()
    {
        return $this->belongsTo(CampaignUserRole::class);
    }

    public function nodeJoin()
    {
        return $this->hasMany(NodeJoin::class);
    }
}
