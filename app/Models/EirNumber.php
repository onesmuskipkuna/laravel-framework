<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EirNumber extends Model
{
    use HasFactory;

    protected $fillable = [
        'container_id',
        'eir_number',
        'issue_date',
        'issued_by',
        'remarks',
    ];

    protected $casts = [
        'issue_date' => 'datetime',
    ];

    public function container(): BelongsTo
    {
        return $this->belongsTo(Container::class);
    }
}
