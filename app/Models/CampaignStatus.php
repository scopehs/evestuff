<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CampaignStatus extends Model
{
    use HasFactory;

    protected $guarded = [];

    public $timestamps = false;

    public function campagain()
    {
        return $this->hasMany(Campaign::class);
    }

    public function customcampagain()
    {
        return $this->hasMany(CustomCampaign::class);
    }

    protected $casts = [
        'id' => 'integer',
    ];
}
