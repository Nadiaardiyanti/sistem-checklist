<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BeritaAcaraVersion extends Model
{
    protected $fillable = [
        'berita_acara_id',
        'user_id',
        'nomor_ba',
        'tanggal_surat',
        'nama_mitra',
        'luas_tenant',
        'posisi_tenant',
        'checklist_data',
        'signature_data',
        'action',
    ];

    protected $casts = [
        'tanggal_surat' => 'date',
        'luas_tenant' => 'decimal:2',
    ];

    public function beritaAcara(): BelongsTo
    {
        return $this->belongsTo(
            BeritaAcara::class
        );
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(
            User::class
        );
    }
}