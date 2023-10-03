<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class OperationInfoRecon extends Model
{
    use HasFactory;
    protected $guarded = [];

    /**
     * Get the user that owns the OperationInfoRecon
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function main(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    /**
     * Get the system associated with the OperationInfoRecon
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function system(): HasOne
    {
        return $this->hasOne(System::class, 'id', 'system_id');
    }

    /**
     * Get the alliance associated with the OperationInfoRecon
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function alliance(): HasOne
    {
        return $this->hasOne(Alliance::class, 'id', 'alliance_id');
    }

    /**
     * Get the corp associated with the OperationInfoRecon
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function corp(): HasOne
    {
        return $this->hasOne(Corp::class, 'id', 'corp_id');
    }

    /**
     * Get the Status associated with the OperationInfoRecon
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function Status(): HasOne
    {
        return $this->hasOne(OperationInfoReconStatus::class, 'id', 'operation_info_recon_status_id');
    }

    /**
     * Get the fleet that owns the OperationInfoRecon
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function fleet(): BelongsTo
    {
        return $this->belongsTo(OperationInfoFleet::class, 'operation_info_fleet_id', 'id');
    }

    /**
     * Get the fleetRole associated with the OperationInfoRecon
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function fleetRole(): HasOne
    {
        return $this->hasOne(OperationInfoFleetReconRole::class, 'id', 'role_id');
    }
}
