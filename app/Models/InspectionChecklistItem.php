<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class InspectionChecklistItem extends Model
{
    protected $fillable = [
        'inspection_id',
        'section_code',
        'section_name',
        'code',
        'criteria',
        'status',
        'notes',
        'is_custom',
        'sort_order',
    ];

    protected function casts(): array
    {
        return [
            'is_custom' => 'boolean',
            'sort_order' => 'integer',
        ];
    }

    public function inspection(): BelongsTo
    {
        return $this->belongsTo(Inspection::class);
    }

    public function findings(): HasMany
    {
        return $this->hasMany(InspectionFinding::class, 'checklist_item_id');
    }
}
