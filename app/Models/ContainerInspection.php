<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ContainerInspection extends Model
{
    use HasFactory;

    protected $fillable = [
        'container_id',
        'surveyor_name',
        'inspection_date',
        'condition',
        'inspection_notes',
        'action',
    ];

    protected $casts = [
        'inspection_date' => 'datetime',
    ];

    public function container(): BelongsTo
    {
        return $this->belongsTo(Container::class);
    }

    public function damages(): HasMany
    {
        return $this->hasMany(ContainerDamage::class, 'inspection_id');
    }
}
