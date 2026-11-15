<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class InspectionFact extends Model
{
    protected $guarded = ['id'];

    protected function casts(): array
    {
        return [
            'inspection_date'  => 'date',
            'extracted_at'     => 'datetime',
            'compliance_score' => 'float',
        ];
    }

    public function inspection(): BelongsTo
    {
        return $this->belongsTo(Inspection::class);
    }
}
