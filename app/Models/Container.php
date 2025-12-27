<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Container extends Model
{
    use HasFactory;

    protected $fillable = [
        'container_number',
        'type',
        'shipping_line_id',
        'status',
        'booking_details',
    ];

    public function shippingLine(): BelongsTo
    {
        return $this->belongsTo(ShippingLine::class);
    }

    public function gateEntries(): HasMany
    {
        return $this->hasMany(GateEntry::class);
    }

    public function inspections(): HasMany
    {
        return $this->hasMany(ContainerInspection::class);
    }

    public function damages(): HasMany
    {
        return $this->hasMany(ContainerDamage::class);
    }

    public function photos(): HasMany
    {
        return $this->hasMany(ContainerPhoto::class);
    }

    public function eirNumber(): HasOne
    {
        return $this->hasOne(EirNumber::class);
    }

    public function storageInstructions(): HasMany
    {
        return $this->hasMany(StorageInstruction::class);
    }

    public function interchangeDocuments(): HasMany
    {
        return $this->hasMany(InterchangeDocument::class);
    }

    public function releases(): HasMany
    {
        return $this->hasMany(ContainerRelease::class);
    }
}
