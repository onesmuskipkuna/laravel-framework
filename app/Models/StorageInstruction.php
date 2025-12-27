<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StorageInstruction extends Model
{
    use HasFactory;

    protected $fillable = [
        'container_id',
        'shipping_line_id',
        'instructions',
        'storage_location',
        'instruction_date',
        'instruction_source',
        'is_matched',
    ];

    protected $casts = [
        'instruction_date' => 'datetime',
        'is_matched' => 'boolean',
    ];

    public function container(): BelongsTo
    {
        return $this->belongsTo(Container::class);
    }

    public function shippingLine(): BelongsTo
    {
        return $this->belongsTo(ShippingLine::class);
    }
}
