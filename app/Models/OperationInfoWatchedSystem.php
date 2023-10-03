<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OperationInfoWatchedSystem extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function operationInfo()
    {
        return $this->belongsTo(OperationInfo::class);
    }

    public function system()
    {
        return $this->belongsTo(System::class);
    }
}
