<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Truck extends Model
{
    use HasFactory;

    protected $fillable = [
        'plate_number',
        'model',
        'type',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function gateEntries(): HasMany
    {
        return $this->hasMany(GateEntry::class);
    }

    public function containerReleases(): HasMany
    {
        return $this->hasMany(ContainerRelease::class);
    }
}
