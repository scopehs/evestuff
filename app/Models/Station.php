<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Models\Activity;
use Spatie\Activitylog\Traits\LogsActivity;

class Station extends Model
{
    use HasFactory;
    use LogsActivity;

    public function tapActivity(Activity $activity, string $eventName)
    {
        if ($activity->properties['attributes']['log_helper'] == 1) {
            $activity->description = 'Status Changed';
        } elseif ($activity->properties['attributes']['log_helper'] == 2) {
            $activity->description = 'Added Timer';
        } elseif ($activity->properties['attributes']['log_helper'] == 3) {
            $activity->description = 'Edited Timer';
        } elseif ($activity->properties['attributes']['log_helper'] == 4) {
            $activity->description = 'Rejected Timer';
        } elseif ($activity->properties['attributes']['log_helper'] == 5) {
            $activity->description = 'Approved Timer';
        } else {
            $activity->description = 'Station Updated';
        }

        if ($eventName == 'created') {
            $activity->description = 'Station Added';
        }

        if ($eventName == 'deleted') {
            $activity->description = 'Station Removed';
        }
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly([
                'id',
                'name',
                'system_id',
                'item_id',
                'corp_id',
                'alliance_id',
                'user_id',
                'station_status_id',
                'status.name',
                'gunner_id',
                'out_time',
                'repair_time',
                'ammo_request_id',
                'status_update',
                'show_on_main',
                'show_on_rc',
                'show_on_rc_move',
                'show_on_coord',
                'added_by_user_id',
                'r_cored',
                'rc_fc_id',
                'rc_recon_id',
                'rc_gsol_id',
                'rc_id',
                'log_helper'
            ])
            ->useLogName('Stations')
            ->dontLogIfAttributesChangedOnly([
                'updated_at',
                'r_hash',
                'r_updated_at',
                'r_fitted',
                'r_capital_shipyard',
                'r_hyasyoda',
                'r_invention',
                'r_manufacturing',
                'r_research',
                'r_supercapital_shipyard',
                'r_biochemical',
                'r_hybrid',
                'r_moon_drilling',
                'r_reprocessing',
                'r_point_defense',
                'r_dooms_day',
                'r_guide_bombs',
                'r_anti_cap',
                'r_anti_subcap',
                'r_t2_rigged',
                'r_cloning',
                'r_composite',
                'added_from_recon',
                'attack_notes',
                'attack_adash_link',
                'import_flag',

            ]);
        // Chain fluent methods for configuration options
    }

    protected $guarded = [];

    public function system()
    {
        return $this->belongsTo(System::class);
    }

    public function region()
    {
        return $this->hasOneThrough(
            Region::class,
            System::class,
            'id',
            'id',
            'system_id',
            'region_id'
        );
    }

    public function alliance()
    {
        return $this->hasOneThrough(
            Alliance::class,
            Corp::class,
            'id',
            'id',
            'corp_id',
            'alliance_id'
        );
    }

    public function notification()
    {
        return $this->hasOne(StationNotification::class);
    }

    public function item()
    {
        return $this->belongsTo(Item::class);
    }

    public function addedBy()
    {
        return $this->belongsTo(User::class, 'added_by_user_id', 'id');
    }

    public function status()
    {
        return $this->belongsTo(StationStatus::class, 'station_status_id');
    }

    public function fc()
    {
        return $this->belongsTo(RcFcUsers::class, 'rc_fc_id', 'id');
    }

    public function recon()
    {
        return $this->belongsTo(RcReconUsers::class, 'rc_recon_id', 'id');
    }

    public function gsoluser()
    {
        return $this->belongsTo(RcGsolUsers::class, 'rc_gsol_id', 'id');
    }

    public function fit()
    {
        return $this->belongsToMany(StationItems::class, 'station_item_joins', 'station_id', 'station_item_id')->withPivot('id');
    }

    public function corp()
    {
        return $this->belongsTo(Corp::class);
    }

    public function logs()
    {
        return $this->hasMany(Activity::class, 'subject_id');
    }

    public function notes()
    {
        return $this->hasMany(StationNotes::class, 'station_id', 'id')->orderBy('created_at', "desc");
    }

    public function getListAttribute()
    {
        $test = getStationWatchListIDs($this->id);
        $watchLists = StationWatchList::whereActive(1)->whereIn('id', $test)->select(['id', 'name', 'description'])->get();
        return  $watchLists;
    }

    protected $casts = [
        'id' => 'integer',
        'system_id' => 'integer',
        'corp_id' => 'integer',
        'alliance_id' => 'integer',
        'item_id' => 'integer',
        'user_id' => 'integer',
        'station_status_id' => 'integer',
        'gunner_id' => 'integer',
        'ammo_request_id' => 'integer',
        'r_capital_shipyard' => 'integer',
        'r_hyasyoda' => 'integer',
        'r_invention' => 'integer',
        'r_manufacturing' => 'integer',
        'r_research' => 'integer',
        'r_supercapital_shipyard' => 'integer',
        'r_biochemical' => 'integer',
        'r_hybrid' => 'integer',
        'r_moon_drilling' => 'integer',
        'r_reprocessing' => 'integer',
        'r_point_defense' => 'integer',
        'r_dooms_day' => 'integer',
        'r_anti_subcap' => 'integer',
        'r_guide_bombs' => 'integer',
        'r_anti_cap' => 'integer',
        'r_t2_rigged' => 'integer',
        'r_cloning' => 'integer',
        'r_composite' => 'integer',
        'r_cored' => 'integer',
        'show_on_main' => 'integer',
        'show_on_chill' => 'integer',
        'show_on_welp' => 'integer',
        'show_on_rc' => 'integer',
        'show_on_rc_move' => 'integer',
        'show_on_coord' => 'integer',
        'rc_fc_id' => 'integer',
        'rc_gsol_id' => 'integer',
        'rc_recon_id' => 'integer',
        'added_by_user_id' => 'integer',
        'corp_id' => 'integer',

    ];
}
