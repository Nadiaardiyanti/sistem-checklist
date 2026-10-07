<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class BeritaAcara extends Model
{
    protected $fillable = [
        'nomor_ba',
        'tanggal_surat',
        'nama_mitra',
        'luas_tenant',
        'posisi_tenant',
        'status',
        'created_by',
    ];

    protected $casts = [
        'tanggal_surat' => 'date',
        'luas_tenant' => 'decimal:2',
    ];

    /*
    |--------------------------------------------------------------------------
    | RELASI USER / PEMBUAT BA
    |--------------------------------------------------------------------------
    */

    public function creator(): BelongsTo
    {
        return $this->belongsTo(
            User::class,
            'created_by'
        );
    }


    /*
    |--------------------------------------------------------------------------
    | RELASI CHECKLIST
    |--------------------------------------------------------------------------
    */

    public function checklistItems(): HasMany
    {
        return $this->hasMany(
            ChecklistItem::class
        );
    }


    /*
    |--------------------------------------------------------------------------
    | RELASI TANDA TANGAN
    |--------------------------------------------------------------------------
    */

    public function signatures(): HasMany
    {
        return $this->hasMany(
            Signature::class
        );
    }


    /*
    |--------------------------------------------------------------------------
    | RELASI VERSION HISTORY
    |--------------------------------------------------------------------------
    */

    public function versions(): HasMany
    {
        return $this->hasMany(
            BeritaAcaraVersion::class
        );
    }

    public function activityLogs(): HasMany
    {
        return $this->hasMany(
            ActivityLog::class
        );
    }
}