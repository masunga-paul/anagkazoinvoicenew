<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Invoice extends Model
{
    use HasFactory;

    protected $fillable = [
        'invoice_number',
        'customer_id',
        'customer_name',
        'billing_address',
        'issuer_name',
        'issuer_phone',
        'issue_date',
        'due_date',
        'payment_terms',
        'status',
        'subtotal_tzs',
        'discount_tzs',
        'tax_rate_percent',
        'tax_type',
        'tax_amount_tzs',
        'total_amount_tzs',
        'amount_paid_tzs',
        'payment_method',
        'selected_payment_method_ids',
        'notes',
    ];

    protected $casts = [
        'issue_date' => 'date',
        'due_date' => 'date',
        'subtotal_tzs' => 'decimal:2',
        'discount_tzs' => 'decimal:2',
        'tax_rate_percent' => 'decimal:2',
        'tax_amount_tzs' => 'decimal:2',
        'total_amount_tzs' => 'decimal:2',
        'amount_paid_tzs' => 'decimal:2',
        'selected_payment_method_ids' => 'array',
    ];

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(InvoiceItem::class);
    }

    public function getPaymentMethodsListAttribute()
    {
        if (! empty($this->selected_payment_method_ids) && is_array($this->selected_payment_method_ids)) {
            $methods = PaymentMethod::whereIn('id', $this->selected_payment_method_ids)->get();
            if ($methods->isNotEmpty()) {
                return $methods;
            }
        }

        return PaymentMethod::where('is_active', true)->get();
    }

    public static function generateKariakooInvoiceNumber(): string
    {
        $latest = static::orderByDesc('id')->first();
        $nextNumber = 1;

        if ($latest) {
            if (preg_match('/(?:INV\s*DSM|INV-DSM)(?:\s*|-|\/)?(?:\d{4}-)?(\d+)/i', $latest->invoice_number, $matches)) {
                $nextNumber = ((int) $matches[1]) + 1;
            } else {
                $nextNumber = static::count() + 1;
            }
        }

        do {
            $candidate = 'INV DSM ' . str_pad((string) $nextNumber, 3, '0', STR_PAD_LEFT);
            if (! static::where('invoice_number', $candidate)->exists()) {
                return $candidate;
            }
            $nextNumber++;
        } while ($nextNumber < 1000000);

        return $candidate;
    }

    public function getPaymentStatusAttribute(): string
    {
        if ($this->status === 'paid' || (float) $this->amount_paid_tzs >= (float) $this->total_amount_tzs) {
            return 'paid';
        }

        if ($this->due_date && $this->due_date->isPast() && ! $this->due_date->isToday()) {
            return 'overdue';
        }

        if ((float) $this->amount_paid_tzs > 0) {
            return 'partial';
        }

        return 'pending';
    }

    public function getPaymentStatusLabelAttribute(): string
    {
        return match ($this->payment_status) {
            'paid' => 'Paid',
            'overdue' => 'Overdue',
            'partial' => 'Partially Paid',
            default => 'Pending',
        };
    }

    public function getPaymentStatusBadgeClassAttribute(): string
    {
        return match ($this->payment_status) {
            'paid' => 'bg-emerald-50 text-emerald-700 border border-emerald-200/80',
            'overdue' => 'bg-rose-50 text-rose-700 border border-rose-200/80',
            'partial' => 'bg-amber-50 text-amber-700 border border-amber-200/80',
            default => 'bg-blue-50 text-blue-700 border border-blue-200/80',
        };
    }

    public function getDaysOverdueAttribute(): int
    {
        if ($this->payment_status === 'overdue' && $this->due_date) {
            return (int) $this->due_date->diffInDays(now()->startOfDay());
        }

        return 0;
    }

    public function getBalanceTzsAttribute(): float
    {
        return max(0, (float) $this->total_amount_tzs - (float) $this->amount_paid_tzs);
    }

    public function getFormattedBalanceAttribute(): string
    {
        return 'TZS '.number_format($this->balance_tzs, 0, '.', ',');
    }

    public function scopePaid($query)
    {
        return $query->where('status', 'paid')
            ->orWhereColumn('amount_paid_tzs', '>=', 'total_amount_tzs');
    }

    public function scopeOverdue($query)
    {
        return $query->where('status', '!=', 'paid')
            ->whereColumn('amount_paid_tzs', '<', 'total_amount_tzs')
            ->whereNotNull('due_date')
            ->whereDate('due_date', '<', now()->startOfDay());
    }

    public function scopePending($query)
    {
        return $query->where('status', '!=', 'paid')
            ->whereColumn('amount_paid_tzs', '<', 'total_amount_tzs')
            ->where(function ($q) {
                $q->whereNull('due_date')
                    ->orWhereDate('due_date', '>=', now()->startOfDay());
            });
    }

    public function getFormattedTotalAttribute(): string
    {
        return 'TZS '.number_format((float) $this->total_amount_tzs, 0, '.', ',');
    }

    public function getFormattedSubtotalAttribute(): string
    {
        return 'TZS '.number_format((float) $this->subtotal_tzs, 0, '.', ',');
    }

    public function getFormattedTaxAttribute(): string
    {
        return 'TZS '.number_format((float) $this->tax_amount_tzs, 0, '.', ',');
    }

    public function getFormattedDiscountAttribute(): string
    {
        return 'TZS '.number_format((float) $this->discount_tzs, 0, '.', ',');
    }

    public function getCustomerTierAttribute(): string
    {
        if ($this->customer) {
            return $this->customer->tier;
        }

        if ((float) $this->total_amount_tzs >= 100000000) {
            return 'premium';
        }

        if ((float) $this->total_amount_tzs >= 50000000) {
            return 'medium';
        }

        return 'standard';
    }

    public function getIsCustomerPremiumAttribute(): bool
    {
        return $this->customer_tier === 'premium';
    }
}

