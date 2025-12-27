<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Driver extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'phone',
        'license_number',
        'id_number',
        'license_expiry',
        'is_active',
    ];

    protected $casts = [
        'license_expiry' => 'date',
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
