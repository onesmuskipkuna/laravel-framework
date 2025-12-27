<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Tariff extends Model
{
    use HasFactory;

    protected $fillable = [
        'shipping_line_id',
        'damage_code_id',
        'repair_cost',
        'currency',
        'is_active',
    ];

    protected $casts = [
        'repair_cost' => 'decimal:2',
        'is_active' => 'boolean',
    ];

    public function shippingLine(): BelongsTo
    {
        return $this->belongsTo(ShippingLine::class);
    }

    public function damageCode(): BelongsTo
    {
        return $this->belongsTo(DamageCode::class);
    }
}
