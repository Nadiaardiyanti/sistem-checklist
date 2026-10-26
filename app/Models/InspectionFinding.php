<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class InspectionFinding extends Model
{
    protected $fillable = [
        'inspection_id',
        'checklist_item_id',
        'code',
        'criteria',
        'description',
        'sort_order',
    ];

    public function inspection(): BelongsTo
    {
        return $this->belongsTo(Inspection::class);
    }

    public function checklistItem(): BelongsTo
    {
        return $this->belongsTo(
            InspectionChecklistItem::class,
            'checklist_item_id'
        );
    }

    public function photos(): HasMany
    {
        return $this->hasMany(InspectionPhoto::class, 'finding_id')
            ->orderBy('sort_order');
    }
}
