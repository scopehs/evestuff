<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Affiliation extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function alliances()
    {
        return $this->hasMany(Alliance::class);
    }
}
