<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Models\Activity;
use Spatie\Activitylog\Traits\LogsActivity;

class NewUserNode extends Model
{
    use HasFactory;
    use LogsActivity;

    protected $guarded = [];


    public function tapActivity(Activity $activity, string $eventName)
    {
        if ($eventName == 'updated') {
            $activity->description = 'User Node Updated';
        }

        if ($eventName == 'created') {
            $activity->description = 'User Node Made';
        }

        if ($eventName == 'deleted') {
            $activity->description = 'User Node Deleted';
        }
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly([
                'id',
                'primery',
                'node_id',
                'node',
                'notes',
                'node.system',
                'end_time',
                'node_status_id',
                'nodeStatus',
                'opUser'
            ])
            ->useLogName('User Node')
            ->dontLogIfAttributesChangedOnly([
                'updated_at',
                'input_time',
                'base_time',

            ]);
        // Chain fluent methods for configuration options
    }

    public function opUser()
    {
        return $this->belongsTo(OperationUser::class, 'operation_user_id', 'id');
    }

    public function node()
    {
        return $this->belongsTo(NewSystemNode::class, 'node_id', 'id');
    }

    public function nodeStatus()
    {
        return $this->belongsTo(CampaignSystemStatus::class, 'node_status_id', 'id');
    }
}
