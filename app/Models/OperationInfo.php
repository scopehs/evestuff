<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Models\Activity;
use Spatie\Activitylog\Traits\LogsActivity;

class OperationInfo extends Model
{
    use HasFactory;


    use LogsActivity;

    protected $guarded = [];


    public function tapActivity(Activity $activity, string $eventName)
    {
        if ($eventName == 'updated') {
            $activity->description = 'Operation Updated';
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
                'name',
                'start',
                'planing_op_posted',
                'planing_op_pre_ping',
                'planing_op_recon_alerted',
                'planing_op_capital_fc_found',
                'planing_op_fc_found',
                'planing_op_doctrmes_decoded',
                'planing_op_allies_infored',
                'form_op_fc_ready',
                'form_op_capital_fc_ready',
                'form_op_recon_ready',
                'form_op_scouts_ready',
                'form_op_gsol_ready',
                'form_op_blackhand_ready',
                'form_op_gsfoe_ready',
                'form_op_allies_ready',
                'post_op_defrief_done',
                'post_op_scouts_done',
                'post_op_recon_done',
                'post_op_fc_done',
                'post_op_coord_done',
                'primary_coord_id',
                'primary_recon_id',
                'info',
                'status_id',

            ])
            ->useLogName('Operation Info')
            ->dontLogIfAttributesChangedOnly([
                'updated_at',

            ]);
        // Chain fluent methods for configuration options
    }


    public function status()
    {
        return $this->hasOne(OperationInfoStatus::class, 'id', 'status_id');
    }

    /**
     * Get all of the comments for the OperationInfo
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function messages(): HasMany
    {
        return $this->hasMany(OperationInfoMessage::class, 'operation_info_id', 'id')->orderBy('id', 'desc');
    }


    public function recons(): HasMany
    {
        return $this->hasMany(OperationInfoRecon::class, 'operation_info_id', 'id');
    }


    /**
     * Get all of the comments for the OperationInfo
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function fleets(): HasMany
    {
        return $this->hasMany(OperationInfoFleet::class, 'operation_info_id', 'id');
    }


    /**
     * The systems that belong to the OperationInfo
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function systems(): BelongsToMany
    {
        return $this->belongsToMany(System::class, 'operation_info_systems', 'operation_info_id', 'system_id')->withPivot('notes', 'new_operation_id', "jammed_status", "cynos_needed");
    }

    /**
     * The systems that belong to the OperationInfo
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function campaigns(): BelongsToMany
    {
        return $this->belongsToMany(NewCampaign::class, 'new_campaign_operations', 'operation_id', 'campaign_id', 'operation_id');
    }

    /**
     * Get the operation that owns the OperationInfo
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function operation(): BelongsTo
    {
        return $this->belongsTo(NewOperation::class, 'operation_id', 'id');
    }

    /**
     * Get the user associated with the OperationInfo
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function dankop(): HasOne
    {
        return $this->hasOne(DankOperation::class, 'operation_info_id', 'id');
    }

    public function watchSystems(): BelongsToMany
    {
        return $this->belongsToMany(System::class, 'operation_info_watched_systems', 'operation_info_id', 'system_id');
    }
}
