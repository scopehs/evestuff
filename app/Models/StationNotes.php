<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StationNotes extends Model
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

    public function station()
    {
        return $this->belongsTo(Station::class);
    }
}
