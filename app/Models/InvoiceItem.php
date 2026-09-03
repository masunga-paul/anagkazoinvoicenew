<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class InvoiceItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'invoice_id',
        'tyre_product_id',
        'item_description',
        'unit_label',
        'quantity',
        'unit_price_tzs',
        'total_price_tzs',
    ];

    protected $casts = [
        'quantity' => 'integer',
        'unit_price_tzs' => 'decimal:2',
        'total_price_tzs' => 'decimal:2',
    ];

    public function invoice(): BelongsTo
    {
        return $this->belongsTo(Invoice::class);
    }

    public function tyreProduct(): BelongsTo
    {
        return $this->belongsTo(TyreProduct::class);
    }
}
