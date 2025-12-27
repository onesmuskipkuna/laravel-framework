<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ContainerRelease extends Model
{
    use HasFactory;

    protected $fillable = [
        'container_id',
        'release_order_number',
        'truck_id',
        'driver_id',
        'release_date',
        'release_order_details',
        'validated_at_gate_b',
        'gate_b_validation_time',
    ];

    protected $casts = [
        'release_date' => 'datetime',
        'gate_b_validation_time' => 'datetime',
        'validated_at_gate_b' => 'boolean',
    ];

    public function container(): BelongsTo
    {
        return $this->belongsTo(Container::class);
    }

    public function truck(): BelongsTo
    {
        return $this->belongsTo(Truck::class);
    }

    public function driver(): BelongsTo
    {
        return $this->belongsTo(Driver::class);
    }
}
