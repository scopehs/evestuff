<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Models\Activity;
use Spatie\Activitylog\Traits\LogsActivity;

class HotRegion extends Model
{
    use HasFactory;
    use LogsActivity;

    protected $guarded = [];

    public function tapActivity(Activity $activity, string $eventName)
    {
        if ($eventName == 'updated') {
            $activity->description = 'Hot Region Updated';
        }

        if ($eventName == 'created') {

            $activity->description = 'Hot Region Added';
        }

        if ($eventName == 'deleted') {
            $activity->description = 'Hot Region Deleted';
        }
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly([
                'id',
                'region_id',
                'region.region_name',
                'update',
                'show_fcs'
            ])
            ->useLogName('Hot Area')
            ->dontLogIfAttributesChangedOnly([
                'updated_at',
            ]);
        // Chain fluent methods for configuration options
    }

    public function region()
    {
        return $this->belongsTo(Region::class);
    }
}
