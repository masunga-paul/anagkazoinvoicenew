<?php

use App\Livewire\Invoices\CreateInvoice;
use App\Livewire\Invoices\InvoiceList;
use App\Models\Customer;
use App\Models\Invoice;
use App\Models\TyreProduct;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->admin = User::factory()->create([
        'email' => 'admin@anagkazo.co.tz',
        'role' => 'admin',
    ]);

    $this->staff = User::factory()->create([
        'email' => 'staff@anagkazo.co.tz',
        'role' => 'staff',
    ]);

    $this->product = TyreProduct::create([
        'brand' => 'Triangle',
        'pattern' => 'TR668 Heavy Commercial Radial',
        'size' => '315/80R22.5',
        'category' => 'truck_bus_radial',
        'sku' => 'TYR-TRI-31580225',
        'unit_price_tzs' => 750000,
        'stock_quantity' => 10,
        'is_active' => true,
    ]);

    $this->customer = Customer::create([
        'name' => 'Kariakoo Express Cargo Ltd',
        'phone' => '+255712345678',
        'billing_address' => 'Plot 10, Msimbazi Street, Kariakoo',
        'customer_type' => 'corporate_fleet',
    ]);
});

test('discount defaults to zero and can be edited by both admin and staff', function () {
    // Admin test
    Livewire::actingAs($this->admin)
        ->test(CreateInvoice::class)
        ->assertSet('discount_tzs', 0)
        ->set('discount_tzs', 50000)
        ->assertSet('discount_tzs', 50000);

    // Staff test
    Livewire::actingAs($this->staff)
        ->test(CreateInvoice::class)
        ->assertSet('discount_tzs', 0)
        ->set('discount_tzs', 75000)
        ->assertSet('discount_tzs', 75000);
});

test('stock decrements dynamically when an invoice is issued', function () {
    expect($this->product->stock_quantity)->toBe(10);

    Livewire::actingAs($this->admin)
        ->test(CreateInvoice::class)
        ->set('customer_id', $this->customer->id)
        ->set('customer_name', $this->customer->name)
        ->set('billing_address', $this->customer->billing_address)
        ->set('issue_date', now()->format('Y-m-d'))
        ->set('due_date', now()->addDays(7)->format('Y-m-d'))
        ->set('discount_tzs', 0)
        ->set('items', [
            [
                'tyre_product_id' => $this->product->id,
                'item_description' => 'Triangle 315/80R22.5 TR668',
                'quantity' => 4,
                'unit_label' => 'tyres',
                'unit_price' => 750000,
                'amount' => 3000000,
            ],
        ])
        ->call('saveInvoice', 'issued');

    $this->product->refresh();
    expect($this->product->stock_quantity)->toBe(6);
});

test('impossible to invoice more stocks than available in depot inventory', function () {
    expect($this->product->stock_quantity)->toBe(10);

    // Attempting to invoice 15 tyres when only 10 are in stock
    Livewire::actingAs($this->staff)
        ->test(CreateInvoice::class)
        ->set('customer_id', $this->customer->id)
        ->set('customer_name', $this->customer->name)
        ->set('billing_address', $this->customer->billing_address)
        ->set('issue_date', now()->format('Y-m-d'))
        ->set('due_date', now()->addDays(7)->format('Y-m-d'))
        ->set('discount_tzs', 0)
        ->set('items', [
            [
                'tyre_product_id' => $this->product->id,
                'item_description' => 'Triangle 315/80R22.5 TR668',
                'quantity' => 15, // Exceeds 10
                'unit_label' => 'tyres',
                'unit_price' => 750000,
                'amount' => 11250000,
            ],
        ])
        ->call('saveInvoice', 'issued')
        ->assertHasErrors(['items']);

    // Ensure database stock was NOT changed
    $this->product->refresh();
    expect($this->product->stock_quantity)->toBe(10);
    expect(Invoice::count())->toBe(0);
});

test('stock is restored when an issued invoice is deleted', function () {
    expect($this->product->stock_quantity)->toBe(10);

    // Issue invoice with 3 tyres
    Livewire::actingAs($this->admin)
        ->test(CreateInvoice::class)
        ->set('customer_id', $this->customer->id)
        ->set('customer_name', $this->customer->name)
        ->set('billing_address', $this->customer->billing_address)
        ->set('issue_date', now()->format('Y-m-d'))
        ->set('due_date', now()->addDays(7)->format('Y-m-d'))
        ->set('discount_tzs', 0)
        ->set('items', [
            [
                'tyre_product_id' => $this->product->id,
                'item_description' => 'Triangle 315/80R22.5 TR668',
                'quantity' => 3,
                'unit_label' => 'tyres',
                'unit_price' => 750000,
                'amount' => 2250000,
            ],
        ])
        ->call('saveInvoice', 'issued');

    $this->product->refresh();
    expect($this->product->stock_quantity)->toBe(7);

    $invoice = Invoice::first();
    expect($invoice)->not->toBeNull();

    // Delete the invoice as admin
    Livewire::actingAs($this->admin)
        ->test(InvoiceList::class)
        ->call('deleteInvoice', $invoice->id)
        ->assertHasNoErrors();

    // Stock should be restored back to 10
    $this->product->refresh();
    expect($this->product->stock_quantity)->toBe(10);
});

test('invoice validation rejects due dates earlier than issue date and negative discounts', function () {
    Livewire::actingAs($this->admin)
        ->test(CreateInvoice::class)
        ->set('customer_name', 'Msimbazi Client')
        ->set('billing_address', 'Kariakoo, DSM')
        ->set('issue_date', '2026-09-10')
        ->set('due_date', '2026-09-01') // Invalid: before issue date
        ->set('items', [
            [
                'tyre_product_id' => $this->product->id,
                'item_description' => 'Triangle 315/80R22.5',
                'quantity' => 2,
                'unit_label' => 'tyres',
                'unit_price' => 750000,
                'amount' => 1500000,
            ],
        ])
        ->call('saveInvoice', 'issued')
        ->assertHasErrors(['due_date']);
});

test('discount cannot exceed subtotal amount', function () {
    Livewire::actingAs($this->admin)
        ->test(CreateInvoice::class)
        ->set('customer_name', 'Msimbazi Client')
        ->set('billing_address', 'Kariakoo, DSM')
        ->set('issue_date', '2026-09-01')
        ->set('due_date', '2026-09-14')
        ->set('items', [
            [
                'tyre_product_id' => $this->product->id,
                'item_description' => 'Triangle 315/80R22.5',
                'quantity' => 1,
                'unit_label' => 'tyres',
                'unit_price' => 500000,
                'amount' => 500000,
            ],
        ])
        ->set('discount_tzs', 600000) // Exceeds 500,000 subtotal
        ->call('saveInvoice', 'issued')
        ->assertHasErrors(['discount_tzs']);
});
