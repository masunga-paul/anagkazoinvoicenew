<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Customer extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'contact_person',
        'phone',
        'email',
        'tin_number',
        'vrn_number',
        'billing_address',
        'customer_type',
        'notes',
    ];

    protected $casts = [];

    public function invoices(): HasMany
    {
        return $this->hasMany(Invoice::class);
    }

    public function getPaidInvoicesCountAttribute(): int
    {
        return $this->invoices->filter(fn ($i) => $i->payment_status === 'paid')->count();
    }

    public function getPendingInvoicesCountAttribute(): int
    {
        return $this->invoices->filter(fn ($i) => in_array($i->payment_status, ['pending', 'partial']))->count();
    }

    public function getOverdueInvoicesCountAttribute(): int
    {
        return $this->invoices->filter(fn ($i) => $i->payment_status === 'overdue')->count();
    }

    public function getTotalSpentTzsAttribute(): float
    {
        return (float) $this->invoices->sum('total_amount_tzs');
    }

    public function getTotalPaidTzsAttribute(): float
    {
        return (float) $this->invoices->sum('amount_paid_tzs');
    }

    public function getBalanceTzsAttribute(): float
    {
        return max(0, $this->total_spent_tzs - $this->total_paid_tzs);
    }

    public function getFormattedBalanceAttribute(): string
    {
        return 'TZS '.number_format($this->balance_tzs, 0, '.', ',');
    }

    public function getHasOverdueInvoicesAttribute(): bool
    {
        return $this->overdue_invoices_count > 0;
    }

    public function getTierAttribute(): string
    {
        $spent = $this->total_spent_tzs;
        if ($spent >= 100000000) {
            return 'premium';
        }

        if ($spent >= 50000000) {
            return 'medium';
        }

        return 'standard';
    }

    public function getIsPremiumAttribute(): bool
    {
        return $this->tier === 'premium';
    }

    public function getTierLabelAttribute(): string
    {
        return match ($this->tier) {
            'premium' => 'Premium Customer',
            'medium' => 'Medium Customer',
            default => 'Standard Customer',
        };
    }

    public function getTierIconAttribute(): string
    {
        return match ($this->tier) {
            'premium' => 'crown',
            'medium' => 'award',
            default => 'user-check',
        };
    }
}

