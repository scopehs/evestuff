<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CampaignUserStatus extends Model
{
    use HasFactory;

    protected $guarded = [];

    public $timestamps = false;

    public function campaignusers()
    {
        return $this->hasMany(CampaignUser::class);
    }

    protected $casts = [
        'id' => 'integer',
    ];
}
