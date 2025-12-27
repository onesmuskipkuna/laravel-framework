<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class InterchangeDocument extends Model
{
    use HasFactory;

    protected $fillable = [
        'container_id',
        'gate_entry_id',
        'document_number',
        'issue_date',
        'recipient_details',
        'copy_sent_to_shipping_line',
        'sent_date',
    ];

    protected $casts = [
        'issue_date' => 'datetime',
        'sent_date' => 'datetime',
        'copy_sent_to_shipping_line' => 'boolean',
    ];

    public function container(): BelongsTo
    {
        return $this->belongsTo(Container::class);
    }

    public function gateEntry(): BelongsTo
    {
        return $this->belongsTo(GateEntry::class);
    }
}
