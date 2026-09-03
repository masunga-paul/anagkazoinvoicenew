<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class TyreProduct extends Model
{
    use HasFactory;

    protected $fillable = [
        'sku',
        'brand',
        'pattern',
        'size',
        'category',
        'unit_price_tzs',
        'wholesale_price_tzs',
        'stock_quantity',
        'reorder_threshold',
        'image_url',
        'is_active',
    ];

    protected $casts = [
        'unit_price_tzs' => 'decimal:2',
        'wholesale_price_tzs' => 'decimal:2',
        'stock_quantity' => 'integer',
        'reorder_threshold' => 'integer',
        'is_active' => 'boolean',
    ];

    public function invoiceItems(): HasMany
    {
        return $this->hasMany(InvoiceItem::class);
    }

    public function getDisplayNameAttribute(): string
    {
        return "{$this->brand} {$this->size} {$this->pattern}";
    }
}
