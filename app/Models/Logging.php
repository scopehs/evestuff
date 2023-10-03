<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Permission\Models\Role;

class Logging extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function campaign()
    {
        return $this->belongsTo(Campaign::class);
    }

    public function sola()
    {
        return $this->belongsTo(CampaignSolaSystem::class);
    }

    public function node()
    {
        return $this->belongsTo(CampaignSystem::class);
    }

    public function type()
    {
        return $this->belongsTo(LoggingType::class, 'logging_type_id');
    }

    public function role()
    {
        return $this->belongsTo(Role::class);
    }

    public function adminUser()
    {
        return $this->belongsTo(User::class, 'admin_user_id');
    }

    protected $casts = [
        'campaign_id' => 'integer',
        'logging_type_id' => 'integer',
        'role_id' => 'integer',

    ];
}
