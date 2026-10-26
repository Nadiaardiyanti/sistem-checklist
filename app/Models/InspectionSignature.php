<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class InspectionSignature extends Model
{
    protected $fillable = [
        'inspection_id',
        'party',
        'company_name',
        'name',
        'position',
        'signature_path',
        'signed_at',
    ];

    protected function casts(): array
    {
        return [
            'signed_at' => 'datetime',
        ];
    }

    public function inspection()
    {
        return $this->belongsTo(Inspection::class);
    }
}
