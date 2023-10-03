<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NewCampaign extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function constellation()
    {
        return $this->belongsTo(Constellation::class);
    }

    public function alliance()
    {
        return $this->belongsTo(Alliance::class);
    }

    public function region()
    {
        return $this->belongsTo(Region::class);
    }

    public function status()
    {
        return $this->belongsTO(CampaignStatus::class);
    }

    public function system()
    {
        return $this->belongsTo(System::class);
    }

    public function structure()
    {
        return $this->belongsTo(Structure::class);
    }

    public function operations()
    {
        return $this->belongsToMany(NewOperation::class, 'new_campaign_operations', 'campaign_id', 'operation_id');
    }
}
