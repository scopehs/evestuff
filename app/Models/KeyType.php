<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Models\Activity;
use Spatie\Activitylog\Traits\LogsActivity;

class KeyType extends Model
{
    use HasFactory;
    use LogsActivity;



    protected $guarded = [];


    public function tapActivity(Activity $activity, string $eventName)
    {
        if ($eventName == 'updated') {
            $activity->description = 'Key Type Updated';
        }

        if ($eventName == 'created') {

            $activity->description = 'Key Type Added';
        }

        if ($eventName == 'deleted') {
            $activity->description = 'Key Type Deleted';
        }
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly([
                'id',
                'name'
            ])
            ->useLogName('Key Type')
            ->dontLogIfAttributesChangedOnly([
                'updated_at',
            ]);
        // Chain fluent methods for configuration options
    }

    public function fleets()
    {
        return $this->belongsToMany(FleetType::class, 'key_fleet_joins');
    }

    public function users()
    {
        return $this->belongsToMany(KeyType::class, 'user_key_joins');
    }
}
