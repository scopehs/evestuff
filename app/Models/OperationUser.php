<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OperationUser extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function userrole()
    {
        return $this->belongsTo(CampaignUserRole::class, 'role_id', 'id');
    }

    public function userstatus()
    {
        return $this->belongsTo(CampaignUserStatus::class, 'user_status_id', 'id');
    }

    public function system()
    {
        return $this->belongsTo(System::class, 'system_id', 'id');
    }

    public function userNode()
    {
        return $this->belongsTo(NewUserNode::class, 'new_user_node_id', 'id');
    }

    public function operation()
    {
        return $this->belongsTo(NewOperation::class, 'operation_id', 'id');
    }

    protected $casts = [
        'id' => 'integer',
        'user_id' => 'integer',
        'entosis' => 'integer',
        'operation_id' => 'integer',
        'user_status_id' => 'integer',
        'system_id' => 'integer',
        'role_id' => 'integer',

    ];
}
