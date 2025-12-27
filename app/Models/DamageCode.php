<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class DamageCode extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'description',
        'component',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function damages(): HasMany
    {
        return $this->hasMany(ContainerDamage::class);
    }

    public function tariffs(): HasMany
    {
        return $this->hasMany(Tariff::class);
    }
}
