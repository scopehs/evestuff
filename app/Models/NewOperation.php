<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Models\Activity;
use Spatie\Activitylog\Traits\LogsActivity;

class NewOperation extends Model
{
    use HasFactory;
    use LogsActivity;

    protected $guarded = [];


    public function tapActivity(Activity $activity, string $eventName)
    {
        if ($eventName == 'updated') {
            if ($activity->properties['attributes']['log_helper'] == 1) {
                $activity->description = 'Read Only Changed';
            } elseif ($activity->properties['attributes']['log_helper'] == 2) {
                $activity->description = 'Priority Changed';
            } else {
                $activity->description = 'Operation Updated';
            }
        }

        if ($eventName == 'created') {
            $activity->description = 'Operation Made';
        }

        if ($eventName == 'deleted') {
            $activity->description = 'Operation Deleted';
        }
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly([
                'id',
                'title',
                'read_only',
                'priority',
                'log_helper'
            ])
            ->useLogName('Operations')
            ->dontLogIfAttributesChangedOnly([
                'updated_at',
                'link',
                'solo',
                'status',

            ]);
        // Chain fluent methods for configuration options
    }

    public function campaign()
    {
        return $this->belongsToMany(NewCampaign::class, 'new_campaign_operations', 'operation_id', 'campaign_id');
    }

    public function alliance()
    {
        return $this->belongsTo(Alliance::class);
    }

    public function users()
    {
        return $this->belongsToMany(User::class, 'operation_user_lists', 'operation_id', 'user_id');
    }
}
