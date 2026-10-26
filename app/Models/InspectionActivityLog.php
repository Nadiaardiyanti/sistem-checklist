<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class InspectionActivityLog extends Model
{
    protected $fillable = [
        'inspection_id',
        'user_id',
        'activity',
        'description',
    ];

    public function inspection(): BelongsTo
    {
        return $this->belongsTo(Inspection::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
