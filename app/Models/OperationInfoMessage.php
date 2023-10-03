<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OperationInfoMessage extends Model
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

    /**
     * Get the user that owns the OperationInfoMessage
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the opeartion that owns the OperationInfoMessage
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */

    public function operation(): BelongsTo
    {
        return $this->belongsTo(OperationInfo::class);
    }
}
