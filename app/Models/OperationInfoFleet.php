<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Models\Activity;

class OperationInfoFleet extends Model
{
    use HasFactory;

    protected $guarded = [];


    public function tapActivity(Activity $activity, string $eventName)
    {
        if ($eventName == 'updated') {
            $activity->description = 'Operation Fleet Updated';
        }

        if ($eventName == 'created') {
            $activity->description = 'Operation Fleet Made';
        }

        if ($eventName == 'deleted') {
            $activity->description = 'Operation Fleet Deleted';
        }
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly([
                'id',
                'name',
                'operation_info_id',
                'gsf_fleet',
                'mumble_id',
                'doctrine_id',
                'alliance_id',
                'fc',
                'boss',
                'mumble',
                'doctrine',
                'alliance',
                'recons'
            ])
            ->useLogName('Operation Fleet')
            ->dontLogIfAttributesChangedOnly([
                'updated_at',
                'show'

            ]);
        // Chain fluent methods for configuration options
    }

    /**
     * Get the user associated with the OperationInfoFleet
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function fc(): HasOne
    {
        return $this->hasOne(OperationInfoUser::class, 'id', 'fc_id');
    }

    public function boss(): HasOne
    {
        return $this->hasOne(OperationInfoUser::class, 'id', 'boss_id');
    }

    public function mumble(): HasOne
    {
        return $this->hasOne(OperationInfoMumble::class, 'id', 'mumble_id');
    }

    public function doctrine(): HasOne
    {
        return $this->hasOne(OperationInfoDoctrine::class, 'id', 'doctrine_id');
    }

    public function alliance(): HasOne
    {
        return $this->hasOne(Alliance::class, 'id', 'alliance_id');
    }

    /**
     * Get all of the recon for the OperationInfoFleet
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function recons(): HasMany
    {
        return $this->hasMany(OperationInfoRecon::class);
    }
}
