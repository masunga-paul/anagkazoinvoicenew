<?php

use App\Livewire\Dashboard\Overview;
use App\Models\Customer;
use App\Models\Invoice;
use App\Models\InvoiceItem;
use App\Models\PaymentMethod;
use App\Models\TyreProduct;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;

uses(RefreshDatabase::class);

test('admin can wipe operational data while preserving users and payment methods', function () {
    $admin = User::factory()->create([
        'email' => 'admin@anagkazo.co.tz',
        'role' => 'admin',
    ]);

    $staff = User::factory()->create([
        'email' => 'staff@anagkazo.co.tz',
        'role' => 'staff',
    ]);

    $paymentMethod = PaymentMethod::create([
        'name' => 'CRDB Bank A/C',
        'type' => 'bank_transfer',
        'account_number_or_till' => '0150123456789',
        'account_name' => 'Anagkazo Autoparts',
        'is_active' => true,
    ]);

    $customer = Customer::create([
        'name' => 'Bakhresa Group Logistics',
        'phone' => '+255712000111',
        'customer_type' => 'corporate_fleet',
        'billing_address' => 'Plot 45, Nyerere Road, Dar es Salaam',
    ]);

    $product = TyreProduct::create([
        'brand' => 'Triangle',
        'pattern' => 'TR668',
        'size' => '315/80R22.5',
        'category' => 'tbr_truck',
        'sku' => 'TRI-31580-001',
        'unit_price_tzs' => 750000,
        'stock_quantity' => 20,
        'is_active' => true,
    ]);

    $invoice = Invoice::create([
        'invoice_number' => 'INV-2026-0001',
        'customer_id' => $customer->id,
        'customer_name' => $customer->name,
        'issue_date' => now(),
        'due_date' => now()->addDays(30),
        'tax_type' => 'inclusive',
        'subtotal_tzs' => 1500000,
        'vat_amount_tzs' => 228813,
        'total_amount_tzs' => 1500000,
        'amount_paid_tzs' => 0,
        'balance_tzs' => 1500000,
        'status' => 'issued',
        'payment_status' => 'pending',
    ]);

    InvoiceItem::create([
        'invoice_id' => $invoice->id,
        'tyre_product_id' => $product->id,
        'item_description' => 'Triangle 315/80R22.5 TR668',
        'unit_label' => 'PCS',
        'quantity' => 2,
        'unit_price_tzs' => 750000,
        'total_price_tzs' => 1500000,
    ]);

    expect(Invoice::count())->toBe(1);
    expect(InvoiceItem::count())->toBe(1);
    expect(Customer::count())->toBe(1);
    expect(TyreProduct::count())->toBe(1);
    expect(User::count())->toBe(2);
    expect(PaymentMethod::count())->toBe(1);

    Livewire::actingAs($admin)
        ->test(Overview::class)
        ->set('confirmResetText', 'DELETE')
        ->call('wipeOperationalData')
        ->assertHasNoErrors()
        ->assertSee('Database cleared successfully');

    // Operational data must be wiped
    expect(Invoice::count())->toBe(0);
    expect(InvoiceItem::count())->toBe(0);
    expect(Customer::count())->toBe(0);
    expect(TyreProduct::count())->toBe(0);

    // Credentials and payment channels must be preserved
    expect(User::count())->toBe(2);
    expect(User::where('email', 'admin@anagkazo.co.tz')->exists())->toBeTrue();
    expect(User::where('email', 'staff@anagkazo.co.tz')->exists())->toBeTrue();
    expect(PaymentMethod::count())->toBe(1);
    expect(PaymentMethod::where('name', 'CRDB Bank A/C')->exists())->toBeTrue();
});

test('staff is redirected away from overview and cannot access overview dashboard', function () {
    $staff = User::factory()->create([
        'email' => 'staff@anagkazo.co.tz',
        'role' => 'staff',
    ]);

    Livewire::actingAs($staff)
        ->test(Overview::class)
        ->assertRedirect(route('invoices.create'));
});

test('reports charts and graphs dynamically calculate to zero and empty state on clean database', function () {
    $admin = User::factory()->create([
        'email' => 'admin@anagkazo.co.tz',
        'role' => 'admin',
    ]);

    Livewire::actingAs($admin)
        ->test(\App\Livewire\Reports\ReportsIndex::class)
        ->assertViewHas('monthsData', function ($months) {
            // All 6 months must show 0 amount and 0 tyres when no invoices exist
            foreach ($months as $m) {
                if ($m['amount'] !== 0.0 && $m['amount'] !== 0) return false;
                if ($m['collected'] !== 0.0 && $m['collected'] !== 0) return false;
                if ($m['tyres'] !== 0) return false;
            }
            return count($months) === 6;
        })
        ->assertSee('0 Units')
        ->assertSee('TZS 0');
});

