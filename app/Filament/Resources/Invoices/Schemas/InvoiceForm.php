<?php

namespace App\Filament\Resources\Invoices\Schemas;

use App\Models\Customer;
use App\Models\Invoice;
use App\Models\TyreProduct;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class InvoiceForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('invoice_number')
                    ->label('Invoice #')
                    ->required()
                    ->default(fn () => Invoice::generateKariakooInvoiceNumber()),

                Select::make('customer_id')
                    ->label('Kariakoo Client')
                    ->relationship('customer', 'name')
                    ->searchable()
                    ->preload()
                    ->live()
                    ->afterStateUpdated(function ($state, callable $set) {
                        if ($customer = Customer::find($state)) {
                            $set('customer_name', $customer->name);
                            $set('billing_address', $customer->billing_address."\nTIN: ".($customer->tin_number ?? 'N/A').' | Tel: '.$customer->phone);
                        }
                    }),

                TextInput::make('customer_name')
                    ->label('Client Name')
                    ->required(),

                Textarea::make('billing_address')
                    ->label('Billing Address & Tax ID')
                    ->columnSpanFull()
                    ->rows(2),

                DatePicker::make('issue_date')
                    ->label('Issue Date')
                    ->default(now())
                    ->required(),

                DatePicker::make('due_date')
                    ->label('Due Date')
                    ->default(now()->addDays(14))
                    ->required(),

                Select::make('payment_terms')
                    ->label('Payment Terms')
                    ->options([
                        'Immediate / Cash' => 'Immediate / Cash',
                        'Net 7' => 'Net 7 Days',
                        'Net 14' => 'Net 14 Days',
                        'Net 30' => 'Net 30 Days',
                    ])
                    ->default('Net 14')
                    ->required(),

                Select::make('status')
                    ->label('Invoice Status')
                    ->options([
                        'draft' => 'Draft',
                        'issued' => 'Issued / Pending',
                        'partially_paid' => 'Partially Paid',
                        'paid' => 'Paid & Cleared',
                        'cancelled' => 'Cancelled',
                    ])
                    ->default('draft')
                    ->required(),

                // Items Repeater
                Repeater::make('items')
                    ->relationship('items')
                    ->label('Tyre Line Items (Kariakoo Stock)')
                    ->columnSpanFull()
                    ->schema([
                        Select::make('tyre_product_id')
                            ->label('Stock Tyre SKU')
                            ->options(TyreProduct::where('is_active', true)->get()->mapWithKeys(function ($prod) {
                                return [$prod->id => "{$prod->brand} {$prod->size} {$prod->pattern} (TZS ".number_format($prod->unit_price_tzs).')'];
                            }))
                            ->searchable()
                            ->live()
                            ->afterStateUpdated(function ($state, callable $set, callable $get) {
                                if ($prod = TyreProduct::find($state)) {
                                    $set('item_description', "{$prod->brand} {$prod->size} {$prod->pattern}");
                                    $set('unit_price_tzs', $prod->unit_price_tzs);
                                    $qty = (int) ($get('quantity') ?: 1);
                                    $set('total_price_tzs', $qty * (float) $prod->unit_price_tzs);
                                }
                            }),

                        TextInput::make('item_description')
                            ->label('Description')
                            ->required(),

                        TextInput::make('unit_label')
                            ->label('Unit')
                            ->default('tyres')
                            ->required(),

                        TextInput::make('quantity')
                            ->numeric()
                            ->default(1)
                            ->minValue(1)
                            ->live()
                            ->afterStateUpdated(function ($state, callable $set, callable $get) {
                                $price = (float) ($get('unit_price_tzs') ?: 0);
                                $set('total_price_tzs', (int) $state * $price);
                            })
                            ->required(),

                        TextInput::make('unit_price_tzs')
                            ->label('Unit Price (TZS)')
                            ->numeric()
                            ->live()
                            ->afterStateUpdated(function ($state, callable $set, callable $get) {
                                $qty = (int) ($get('quantity') ?: 1);
                                $set('total_price_tzs', $qty * (float) $state);
                            })
                            ->required(),

                        TextInput::make('total_price_tzs')
                            ->label('Amount (TZS)')
                            ->numeric()
                            ->readOnly(),
                    ])
                    ->columns(6)
                    ->defaultItems(1),

                TextInput::make('subtotal_tzs')
                    ->label('Subtotal (TZS)')
                    ->numeric()
                    ->default(0)
                    ->required(),

                TextInput::make('discount_tzs')
                    ->label('Discount (TZS)')
                    ->numeric()
                    ->default(0)
                    ->required(),

                TextInput::make('tax_rate_percent')
                    ->label('TRA VAT (%)')
                    ->numeric()
                    ->default(18)
                    ->required(),

                TextInput::make('tax_amount_tzs')
                    ->label('TRA VAT Amount (TZS)')
                    ->numeric()
                    ->default(0)
                    ->required(),

                TextInput::make('total_amount_tzs')
                    ->label('Total Amount (TZS)')
                    ->numeric()
                    ->default(0)
                    ->required(),

                TextInput::make('amount_paid_tzs')
                    ->label('Amount Paid (TZS)')
                    ->numeric()
                    ->default(0)
                    ->required(),

                Select::make('payment_method')
                    ->label('Settlement Channel')
                    ->options([
                        'Cash on Delivery / Kariakoo Workshop' => 'Cash on Delivery / Kariakoo Workshop',
                        'M-Pesa Lipa Namba 5829104' => 'M-Pesa Lipa Namba 5829104',
                        'CRDB Bank (Kariakoo Branch)' => 'CRDB Bank (Kariakoo Branch)',
                        'NMB Bank (Clock Tower Branch)' => 'NMB Bank (Clock Tower Branch)',
                    ]),

                Textarea::make('notes')
                    ->label('Customer Notes & Swahili Trade Terms')
                    ->default('Asante kwa kufanya biashara nasi. Malipo yafanyike kabla ya tarehe ya mwisho kupitia Akaunti zetu za Benki au M-Pesa Lipa Namba.')
                    ->columnSpanFull()
                    ->rows(2),
            ]);
    }
}
