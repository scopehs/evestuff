<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class OperationUserList extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function opUsers()
    {
        return $this->belongsTo(OperationUser::class, 'operation_id', 'id');
    }

    /**
     * Get all of the ownUsers for the OperationUserList
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function ownUsers(): HasMany
    {
        return $this->hasMany(OperationUser::class, 'user_id', 'user_id');
    }
}
