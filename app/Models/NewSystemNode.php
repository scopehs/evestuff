<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Models\Activity;
use Spatie\Activitylog\Traits\LogsActivity;

class NewSystemNode extends Model
{
    use HasFactory;
    use LogsActivity;

    protected $guarded = [];


    public function tapActivity(Activity $activity, string $eventName)
    {
        if ($eventName == 'updated') {

            $activity->description = 'System Node Updated';
        }

        if ($eventName == 'created') {
            $activity->description = 'System Node Made';
        }

        if ($eventName == 'deleted') {
            $activity->description = 'System Node Deleted';
        }
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly([
                'id',
                'name',
                'system_id',
                'campaign_id',
                'node_status',
                'end_time',
                'nodeStatus.name',
                'system.system_name',
                'allUsers',

            ])
            ->useLogName('System Node')
            ->dontLogIfAttributesChangedOnly([
                'updated_at',
                'input_time',
                'base_time'

            ]);
        // Chain fluent methods for configuration options
    }

    public function allUsers()
    {
        return $this->hasMany(NewUserNode::class, 'node_id');
    }

    public function campaign()
    {
        return $this->belongsTo(NewCampaign::class);
    }

    public function nonePrimeNodeUser()
    {
        return $this->allUsers()->where('primery', 0);
    }

    public function primeNodeUser()
    {
        return $this->allUsers()->where('primery', 1);
    }

    public function nodeStatus()
    {
        return $this->belongsTo(CampaignSystemStatus::class, 'node_status', 'id');
    }

    public function system()
    {
        return $this->belongsTo(System::class);
    }
}
