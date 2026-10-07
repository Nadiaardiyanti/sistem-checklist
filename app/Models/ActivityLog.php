<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ActivityLog extends Model
{
    protected $fillable = [
        'user_id',
        'berita_acara_id',
        'activity',
        'description',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(
            User::class
        );
    }

    public function beritaAcara(): BelongsTo
    {
        return $this->belongsTo(
            BeritaAcara::class
        );
    }
}