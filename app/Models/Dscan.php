<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Dscan extends Model
{
    use HasFactory;
    protected $guarded = [];

    protected $fillable = [
        "totals",
        'dscan',
        'corpTotal',
        'allianceTotal',
        'affiliationsTotal',
        'itemsTotals',
        'groupTotals',
        'categoryTotals',



    ];

    protected $casts = [
        'totals' => 'array',
        'dscan' => 'array',
        'corpTotal' => 'array',
        'allianceTotal' => 'array',
        'affiliationsTotal' => 'array',
        'itemsTotals' => 'array',
        'groupTotals' => 'array',
        'categoryTotals' => 'array',


    ];

    public function items()
    {
        return $this->hasMany(DscanItem::class);
    }

    public function madeBy()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function updatedBy()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    public function system()
    {
        return $this->belongsTo(System::class);
    }

    public function locals()
    {
        return $this->belongsToMany(Character::class, 'dscan_locals', 'dscan_id', 'character_id')
            ->withPivot('new', 'left', 'same');
    }

    public function history()
    {
        return $this->hasMany(DscanHistory::class)->orderBy('history_count', 'desc');
    }

    public function messages()
    {
        return $this->hasMany(DscanNote::class)->orderBy('created_at', "desc");
    }
}
