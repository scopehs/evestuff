<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReconTaskSystems extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function reconTask()
    {
        return $this->belongsTo(ReconTasks::class);
    }

    public function system()
    {
        return $this->belongsTo(System::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    protected $casts = [
        'created_at' => 'datetime:Y-m-d H:i:s',
        'updated_at' => 'datetime:Y-m-d H:i:s',
        'id' => 'integer',
        'recon_task_id' => 'integer',
        'system_id' => 'integer',
        'user_id' => 'integer',
    ];
}
