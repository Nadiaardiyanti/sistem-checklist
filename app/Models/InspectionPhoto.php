<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class InspectionPhoto extends Model
{
    protected $fillable = [
        'inspection_id',
        'finding_id',
        'category',
        'file_path',
        'caption',
        'sort_order',
    ];

    public function inspection(): BelongsTo
    {
        return $this->belongsTo(Inspection::class);
    }

    public function finding(): BelongsTo
    {
        return $this->belongsTo(InspectionFinding::class, 'finding_id');
    }
}
