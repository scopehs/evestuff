<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Models\Activity;
use Spatie\Activitylog\Traits\LogsActivity;

class AmmoRequest extends Model
{
    use HasFactory;
    use LogsActivity;

    protected $guarded = [];

    public function tapActivity(Activity $activity, string $eventName)
    {
        if ($eventName == 'updated') {
            $activity->description = 'Updated Ammo Request';
        }

        if ($eventName == 'created') {

            $activity->description = 'Ammo Request Made';
        }

        if ($eventName == 'deleted') {
            $activity->description = 'Ammo Request Deleted';
        }
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly([
                'id',
                'station_id',
                'station.name',
                'current_ammo',
                'request_test',
                'user_id',
                'user.name'
            ])
            ->useLogName('AmmoRequest')
            ->dontLogIfAttributesChangedOnly([
                'updated_at',
                'jabber_ping',
            ]);
        // Chain fluent methods for configuration options
    }
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function station()
    {
        return $this->belongsTo(Station::class);
    }
}
