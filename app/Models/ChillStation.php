<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ChillStation extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function system()
    {
        return $this->belongsTo(System::class);
    }

    public function item()
    {
        return $this->belongsTo(Item::class);
    }

    public function corp()
    {
        return $this->belongsTo(Corp::class);
    }
}
