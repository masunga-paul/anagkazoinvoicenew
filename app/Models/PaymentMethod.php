<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PaymentMethod extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'type',
        'bank_name',
        'account_number_or_till',
        'account_name',
        'branch',
        'logo_url',
        'instructions',
        'is_active',
        'is_default',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'is_default' => 'boolean',
    ];

    public function getFormattedLabelAttribute(): string
    {
        if ($this->type === 'mobile_money') {
            return "{$this->name} (Till: {$this->account_number_or_till})";
        }

        return "{$this->name} - A/C: {$this->account_number_or_till}";
    }
}
