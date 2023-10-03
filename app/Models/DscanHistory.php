<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DscanHistory extends Model
{
    use HasFactory;

    protected $fillable = [
        "totals",
        'dscan',
        'corpsTotal',
        'alliancesTotal',
        'affiliationsTotal',
        'itemTotals',
        'groupTotals',
        'categoryTotals',



    ];

    protected $casts = [
        'totals' => 'array',
        'dscan' => 'array',
        'corpsTotal' => 'array',
        'alliancesTotal' => 'array',
        'affiliationsTotal' => 'array',
        'itemTotals' => 'array',
        'groupTotals' => 'array',
        'categoryTotals' => 'array',


    ];

    public function messages()
    {
        return $this->hasMany(DscanNote::class, 'dscan_id', 'dscan_id')->orderBy('created_at', "desc");
    }
}
