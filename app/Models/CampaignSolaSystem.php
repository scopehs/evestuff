<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CampaignSolaSystem extends Model
{
    use HasFactory;

    protected $guarded = [];

    public $timestamps = false;

    public function system()
    {
        return $this->belongsTo(System::class);
    }

    public function users()
    {
        return $this->belongsTo(User::class);
    }

    protected $casts = [
        'system_id' => 'integer',
        'campaign_id' => 'integer',
        'tidi' => 'integer',
    ];
}
