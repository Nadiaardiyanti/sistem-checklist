<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Models\InspectionActivityLog;

class Inspection extends Model
{
    protected $fillable = [
        'inspection_number',
        'partner_name',
        'brand',
        'room_code',
        'category',
        'location',
        'inspection_type',
        'inspection_date',
        'inspection_time',
        'inspector_name',
        'inspector_position',
        'terminal',
        'status',
        'notes',
        'created_by',
    ];

    protected function casts(): array
    {
        return [
            'inspection_date' => 'date',
        ];
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function checklistItems(): HasMany
    {
        return $this->hasMany(InspectionChecklistItem::class)
            ->orderBy('sort_order');
    }

    public function findings(): HasMany
    {
        return $this->hasMany(InspectionFinding::class)
            ->orderBy('sort_order');
    }

    public function signatures(): HasMany
    {
        return $this->hasMany(InspectionSignature::class);
    }

    public function photos(): HasMany
    {
        return $this->hasMany(InspectionPhoto::class)
            ->orderBy('sort_order');
    }

    public function activityLogs(): HasMany
    {
        return $this->hasMany(InspectionActivityLog::class);
    }
}
