<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Alliance extends Model
{
    //
    protected $guarded = [];

    // public function alliance_id()
    // {
    //     return $this->hasMany(Post::class)->orderBy('created_at', 'DESC');
    // }

    public function campaigns()
    {
        return $this->hasMany(Campaign::class);
    }

    public function corps()
    {
        return $this->hasMany(Corp::class);
    }

    public function affiliation()
    {
        return $this->belongsTo(Affiliation::class);
    }

    public $incrementing = false;

    protected $primaryKey = 'id';

    protected $casts = [
        'id' => 'integer',
        'standingstanding' => 'double',
        'active' => 'integer',
        'color' => 'integer',
    ];
}
