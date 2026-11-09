<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CrmPayment extends Model
{
    use HasFactory;

    protected $table = 'crm_payments';

    protected $fillable = [
        'lead_id',
        'paid_on',
        'currency',
        'amount',
        'fx_rate',
        'received_inr',
        'note',
    ];

    protected $casts = [
        'paid_on' => 'date',
        'amount' => 'decimal:2',
        'fx_rate' => 'decimal:4',
        'received_inr' => 'decimal:2',
    ];

    /**
     * Relationship to Lead (including soft-deleted leads)
     */
    public function lead(): BelongsTo
    {
        return $this->belongsTo(CrmLead::class, 'lead_id')->withTrashed();
    }

    /**
     * Currency symbol helper
     */
    public function getCurrencySymbolAttribute(): string
    {
        return match (strtoupper($this->currency ?? 'INR')) {
            'AED' => 'AED ',
            'USD' => '$',
            default => '₹',
        };
    }

    /**
     * Formatted native amount
     */
    public function getFormattedAmountAttribute(): string
    {
        return $this->currency_symbol . number_format((float) $this->amount, 2);
    }

    /**
     * Formatted INR received amount
     */
    public function getFormattedReceivedInrAttribute(): string
    {
        return '₹' . number_format((float) $this->received_inr, 2);
    }
}
