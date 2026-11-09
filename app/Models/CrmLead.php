<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CrmLead extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'crm_leads';

    protected $fillable = [
        'business_name',
        'market',
        'service',
        'contact_channel',
        'website_url',
        'problem_found',
        'message_sent',
        'response',
        'follow_up_date',
        'status',
        'currency',
        'deal_amount',
        'notes',
    ];

    protected $casts = [
        'message_sent' => 'boolean',
        'follow_up_date' => 'date',
        'deal_amount' => 'decimal:2',
    ];

    protected $appends = [
        'amount_received',
        'pending_payment',
        'payment_status',
    ];

    /**
     * Model lifecycle protection guards
     */
    protected static function booted(): void
    {
        static::deleting(function (CrmLead $lead) {
            $paymentsCount = $lead->payments()->count();
            if ($paymentsCount > 0) {
                throw new \LogicException("Cannot delete this lead because it has {$paymentsCount} recorded payment transaction(s). Financial transaction history must be preserved.");
            }

            if ($lead->status === 'Won') {
                throw new \LogicException("Cannot delete a Won deal. Use the appropriate business status or archival workflow instead.");
            }
        });
    }

    /**
     * Relationship to payments
     */
    public function payments(): HasMany
    {
        return $this->hasMany(CrmPayment::class, 'lead_id')->orderBy('paid_on', 'desc');
    }

    /**
     * Dynamic Accessor: Amount Received (Sum of all completed payments in native currency)
     */
    public function getAmountReceivedAttribute(): float
    {
        if ($this->relationLoaded('payments')) {
            return (float) $this->payments->sum('amount');
        }

        return (float) $this->payments()->sum('amount');
    }

    /**
     * Dynamic Accessor: Total Realized INR (Sum of permanently locked received_inr from all payments)
     */
    public function getTotalReceivedInrAttribute(): float
    {
        if ($this->relationLoaded('payments')) {
            return (float) $this->payments->sum('received_inr');
        }

        return (float) $this->payments()->sum('received_inr');
    }

    /**
     * Dynamic Accessor: Pending Payment (Deal Amount - Amount Received, never negative)
     */
    public function getPendingPaymentAttribute(): float
    {
        $deal = (float) ($this->deal_amount ?? 0);
        $received = $this->amount_received;
        $pending = $deal - $received;

        return max(0.0, round($pending, 2));
    }

    /**
     * Dynamic Accessor: Payment Status (Pending / Partial / Paid)
     */
    public function getPaymentStatusAttribute(): string
    {
        $received = $this->amount_received;
        $deal = (float) ($this->deal_amount ?? 0);

        if ($received <= 0) {
            return 'Pending';
        }

        if ($deal > 0 && $received >= $deal) {
            return 'Paid';
        }

        return 'Partial';
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
     * Formatted deal amount
     */
    public function getFormattedDealAmountAttribute(): string
    {
        return $this->currency_symbol . number_format((float) ($this->deal_amount ?? 0), 2);
    }

    /**
     * Scopes
     */
    public function scopeByStatus($query, $status)
    {
        if (!empty($status)) {
            return $query->where('status', $status);
        }
        return $query;
    }

    public function scopeByMarket($query, $market)
    {
        if (!empty($market)) {
            return $query->where('market', $market);
        }
        return $query;
    }

    public function scopeByService($query, $service)
    {
        if (!empty($service)) {
            return $query->where('service', $service);
        }
        return $query;
    }

    public function scopeSearch($query, $search)
    {
        if (!empty($search)) {
            return $query->where(function ($q) use ($search) {
                $q->where('business_name', 'like', "%{$search}%")
                  ->orWhere('website_url', 'like', "%{$search}%")
                  ->orWhere('notes', 'like', "%{$search}%")
                  ->orWhere('problem_found', 'like', "%{$search}%");
            });
        }
        return $query;
    }
}
