<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TowerNote extends Model
{
    use HasFactory;
    protected $guarded = [];

    protected $fillable = [
        'read_by',
    ];

    protected $casts = [
        'read_by' => 'array',
    ];

    protected $attributes = [

        'read_by' => '{"user_id": []}',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function tower()
    {
        return $this->belongsTo(Tower::class);
    }
}
