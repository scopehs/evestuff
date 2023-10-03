<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CampaignSystemStatus extends Model
{
    use HasFactory;

    protected $guarded = [];

    public $timestamps = false;

    public function campaignsystems()
    {
        return $this->hasMany(CampaignSystem::class);
    }

    public function nodeJoin()
    {
        return $this->hasMany(NodeJoin::class);
    }

    protected $casts = [
        'id' => 'integer',
    ];
}
