<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tower extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function moon()
    {
        return $this->belongsTo(Moon::class);
    }

    public function corp()
    {
        return $this->belongsTo(Corp::class);
    }

    public function item()
    {
        return $this->belongsTo(Item::class);
    }

    public function system()
    {
        return $this->belongsTo(System::class);
    }

    public function status()
    {
        return $this->belongsTo(TowerStatus::class, 'tower_status_id', 'id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function notes()
    {
        return $this->hasMany(TowerNote::class)->orderBy('created_at', "desc");
    }

    public function fit()
    {
        return $this->hasMany(TowerItem::class);
    }
}
