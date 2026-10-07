<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ChecklistItem extends Model
{
    protected $fillable = [
        'berita_acara_id',
        'nama_checklist',
        'is_checked',
    ];

    protected $casts = [
        'is_checked' => 'boolean',
    ];

    public function beritaAcara(): BelongsTo
    {
        return $this->belongsTo(BeritaAcara::class);
    }
}