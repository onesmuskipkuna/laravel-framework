<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ContainerDamage extends Model
{
    use HasFactory;

    protected $fillable = [
        'container_id',
        'damage_code_id',
        'inspection_id',
        'description',
        'repair_cost',
        'status',
    ];

    protected $casts = [
        'repair_cost' => 'decimal:2',
    ];

    public function container(): BelongsTo
    {
        return $this->belongsTo(Container::class);
    }

    public function damageCode(): BelongsTo
    {
        return $this->belongsTo(DamageCode::class);
    }

    public function inspection(): BelongsTo
    {
        return $this->belongsTo(ContainerInspection::class, 'inspection_id');
    }
}
