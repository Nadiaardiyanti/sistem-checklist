<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Signature extends Model
{
    protected $fillable = [
        'berita_acara_id',
        'pihak',
        'nama',
        'jabatan',
        'signature_type',
        'signature_file',
    ];

    public function beritaAcara(): BelongsTo
    {
        return $this->belongsTo(BeritaAcara::class);
    }
}