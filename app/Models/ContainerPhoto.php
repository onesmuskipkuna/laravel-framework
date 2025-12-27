<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ContainerPhoto extends Model
{
    use HasFactory;

    protected $fillable = [
        'container_id',
        'photo_path',
        'photo_type',
        'description',
        'taken_at',
        'sent_to_shipping_line',
    ];

    protected $casts = [
        'taken_at' => 'datetime',
        'sent_to_shipping_line' => 'boolean',
    ];

    public function container(): BelongsTo
    {
        return $this->belongsTo(Container::class);
    }
}
