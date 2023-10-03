<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Models\Activity;
use Spatie\Activitylog\Traits\LogsActivity;

class FleetType extends Model
{
    use HasFactory;
    use LogsActivity;
    protected $guarded = [];

    public function tapActivity(Activity $activity, string $eventName)
    {
        if ($eventName == 'updated') {
            $activity->description = 'Fleet Type Updated';
        }

        if ($eventName == 'created') {

            $activity->description = 'Fleet Type Added';
        }

        if ($eventName == 'deleted') {
            $activity->description = 'Fleet Type Deleted';
        }
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly([
                'id',
                'name'
            ])
            ->useLogName('Fleet Type')
            ->dontLogIfAttributesChangedOnly([
                'updated_at',
            ]);
        // Chain fluent methods for configuration options
    }

    public function keys()
    {
        return $this->belongsToMany(KeyType::class, 'key_fleet_join');
    }
}
