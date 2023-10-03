<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Models\Activity;
use Spatie\Activitylog\Traits\LogsActivity;

class NewCampaignOperation extends Model
{
    use HasFactory;
    use LogsActivity;

    protected $guarded = [];

    public function tapActivity(Activity $activity, string $eventName)
    {
        if ($eventName == 'updated') {
            $activity->description = 'Campaign Operations Updated';
        }

        if ($eventName == 'created') {

            $activity->description = 'Campaign Operations Added';
        }

        if ($eventName == 'deleted') {
            $activity->description = 'Campaign Operations Deleted';
        }
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly([
                'id',
                'campaign_id',
                'operation_id',
                'operation',
                'campaign'
            ])
            ->useLogName('Campaign Operations')
            ->dontLogIfAttributesChangedOnly([
                'updated_at',
            ]);
        // Chain fluent methods for configuration options
    }

    public function link()
    {
        return $this->hasMany(Logging::class);
    }

    public function operation()
    {
        return $this->belongsTo(NewOperation::class, 'operation_id', 'id');
    }

    public function campaign()
    {
        return $this->belongsTo(NewCampaign::class, 'campaign_id', 'id');
    }
}
