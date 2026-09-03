<?php

use App\Livewire\Customers\CustomerManager;
use App\Livewire\Invoices\CreateInvoice;
use App\Livewire\Invoices\InvoiceList;
use App\Livewire\Products\ProductManager;
use App\Livewire\Reports\ReportsIndex;
use App\Livewire\Settings\PaymentMethodsManager;
use App\Models\Customer;
use App\Models\Invoice;
use App\Models\PaymentMethod;
use App\Models\TyreProduct;
use App\Models\User;
use Livewire\Livewire;

beforeEach(function () {
    $user = User::factory()->create([
        'email' => 'admin@anagkazo.co.tz',
        'role' => 'admin',
    ]);
    $this->actingAs($user);
});

test('invoice builder page loads and renders successfully', function () {
    $response = $this->get(route('invoices.create'));

    $response->assertStatus(200);
    $response->assertSee('ANAGKAZO');
    $response->assertSee('logo.png');
    $response->assertSee('Create New Invoice');
    $response->assertSee('Invoice Details');
    $response->assertSee('Preview');
    $response->assertSee('Download PDF');
    $response->assertSee('TAX Inclusive');
    $response->assertSee('TAX Exclusive');
});

test('dashboard page renders with metrics and kariakoo context', function () {
    $response = $this->get(route('dashboard'));

    $response->assertStatus(200);
    $response->assertSee('Kariakoo Central Depot');
    $response->assertSee('Tyre Billing Hub');
});

test('invoices list renders and shows records', function () {
    $response = $this->get(route('invoices.index'));

    $response->assertStatus(200);
    $response->assertSee('Records');
});

test('reports page renders with graphs and analytics', function () {
    $response = $this->get(route('reports.index'));

    $response->assertStatus(200);
    $response->assertSee('Financial');
    $response->assertSee('Analytics');
    $response->assertSee('Monthly Revenue Trajectory');
    $response->assertSee('Brand Sales');
    $response->assertSee('Accounts Receivable Aging');
});

test('products page renders and supports CRUD operations', function () {
    $response = $this->get(route('products.index'));

    $response->assertStatus(200);
    $response->assertSee('Depot Inventory Value');
    $response->assertSee('Add new tyre');

    // Test Create
    $testSku = 'TYR-TEST-9988';
    Livewire::test(ProductManager::class)
        ->set('brand', 'Michelin')
        ->set('pattern', 'X Multiway 3D')
        ->set('size', '315/80R22.5')
        ->set('category', 'truck_bus_radial')
        ->set('sku', $testSku)
        ->set('unit_price_tzs', 950000)
        ->set('wholesale_price_tzs', 900000)
        ->set('stock_quantity', 15)
        ->set('reorder_threshold', 5)
        ->call('saveProduct');

    expect(TyreProduct::where('sku', $testSku)->exists())->toBeTrue();
    $prod = TyreProduct::where('sku', $testSku)->first();
    expect($prod->stock_quantity)->toBe(15);

    // Test Quick Stock Increment
    Livewire::test(ProductManager::class)
        ->call('quickStockChange', $prod->id, 5);

    $prod->refresh();
    expect($prod->stock_quantity)->toBe(20);

    // Test Delete
    Livewire::test(ProductManager::class)
        ->call('deleteProduct', $prod->id);

    expect(TyreProduct::where('sku', $testSku)->exists())->toBeFalse();
});

test('payment methods manager renders and allows adding new Payment', function () {
    $response = $this->get(route('payment-methods.index'));

    $response->assertStatus(200);
    $response->assertSee('Payments');

    Livewire::test(PaymentMethodsManager::class)
        ->set('name', 'Stanbic Bank Kariakoo Branch')
        ->set('type', 'bank_transfer')
        ->set('bank_name', 'Stanbic Bank Tanzania')
        ->set('account_number_or_till', '91200034821')
        ->set('account_name', 'Anagkazo Tyres Ltd')
        ->set('branch', 'Msimbazi / Uhuru, Kariakoo')
        ->call('createPaymentMethod');

    expect(PaymentMethod::where('name', 'Stanbic Bank Kariakoo Branch')->exists())->toBeTrue();
});

test('tax exclusive applies 0 percent tax by default and locks tax amount to 0', function () {
    $tbr = TyreProduct::first();

    Livewire::test(CreateInvoice::class)
        ->call('setTaxType', 'exclusive')
        ->set('items', [
            [
                'tyre_product_id' => $tbr?->id,
                'item_description' => 'Triangle 315/80R22.5 TR668 Heavy Truck Radial',
                'quantity' => 10,
                'unit_label' => 'tyres',
                'unit_price' => 1000000,
                'amount' => 10000000,
            ],
        ])
        ->set('discount_tzs', 500000)
        ->call('recalculateTotals')
        ->assertSet('tax_type', 'exclusive')
        ->assertSet('tax_rate', 0.0)
        ->assertSet('subtotal_tzs', 10000000.0)
        ->assertSet('tax_amount_tzs', 0.0)
        ->assertSet('total_amount_tzs', 9500000.0);
});

test('tax inclusive allows custom tax rate and updates grand total according to calculations', function () {
    $tbr = TyreProduct::first();

    // With 18% tax: Subtotal = 10,000,000, Tax (18%) = 1,800,000, Grand Total = 11,800,000
    $component = Livewire::test(CreateInvoice::class)
        ->call('setTaxType', 'inclusive')
        ->set('tax_rate', 18.0)
        ->set('items', [
            [
                'tyre_product_id' => $tbr?->id,
                'item_description' => 'Triangle 315/80R22.5 TR668 Heavy Truck Radial',
                'quantity' => 1,
                'unit_label' => 'tyres',
                'unit_price' => 10000000,
                'amount' => 10000000,
            ],
        ])
        ->set('discount_tzs', 0)
        ->call('recalculateTotals')
        ->assertSet('subtotal_tzs', 10000000.0)
        ->assertSet('tax_amount_tzs', 1800000.0)
        ->assertSet('total_amount_tzs', 11800000.0);

    // Change TAX to 10% -> Grand total must dynamically update to 11,000,000
    $component->set('tax_rate', 10.0)
        ->assertSet('tax_amount_tzs', 1000000.0)
        ->assertSet('total_amount_tzs', 11000000.0);

    // Change TAX to 0% -> Grand total must dynamically update to 10,000,000
    $component->set('tax_rate', 0.0)
        ->assertSet('tax_amount_tzs', 0.0)
        ->assertSet('total_amount_tzs', 10000000.0);
});

test('customer view and printable invoice indicate TAX Exclusive for excluded invoices', function () {
    $invoice = Invoice::create([
        'invoice_number' => 'INV-TEST-EXCL-01',
        'customer_name' => 'Kariakoo Express Cargo',
        'billing_address' => 'Msimbazi Street, Dar es Salaam',
        'issue_date' => now(),
        'due_date' => now()->addDays(7),
        'payment_terms' => 'Net 7 Days',
        'subtotal_tzs' => 5000000,
        'discount_tzs' => 0,
        'tax_rate_percent' => 0.0,
        'tax_type' => 'exclusive',
        'tax_amount_tzs' => 0,
        'total_amount_tzs' => 5000000,
        'status' => 'issued',
    ]);

    $response = $this->get(route('invoices.show', $invoice));
    $response->assertStatus(200);
    $response->assertSee('TAX EXCLUSIVE INVOICE');
    $response->assertSee('TAX Exclusive (0%)');

    $print = $this->get(route('invoices.print', $invoice));
    $print->assertStatus(200);
    $print->assertSee('TAX EXCLUSIVE INVOICE');
    $print->assertSee('TAX Exclusive (0%)');
});

test('livewire can save an invoice with flexible tax settings', function () {
    $tbr = TyreProduct::first();

    Livewire::test(CreateInvoice::class)
        ->set('customer_name', 'Msimbazi Truckers Union')
        ->set('billing_address', 'Plot 19, Uhuru Street, Kariakoo')
        ->set('issue_date', now()->format('Y-m-d'))
        ->set('due_date', now()->addDays(14)->format('Y-m-d'))
        ->set('tax_type', 'inclusive')
        ->set('tax_rate', 18.0)
        ->set('items', [
            [
                'tyre_product_id' => $tbr?->id,
                'item_description' => 'Triangle 315/80R22.5 TR668 Heavy Truck Radial',
                'quantity' => 4,
                'unit_label' => 'tyres',
                'unit_price' => 750000,
                'amount' => 3000000,
            ],
        ])
        ->call('saveInvoice', 'draft')
        ->assertRedirect();

    expect(Invoice::where('customer_name', 'Msimbazi Truckers Union')->exists())->toBeTrue();
    $inv = Invoice::where('customer_name', 'Msimbazi Truckers Union')->first();
    expect($inv->status)->toBe('draft');
    expect($inv->tax_type)->toBe('inclusive');
    expect($inv->items()->count())->toBe(1);
});

test('downloadPdf saves invoice, streams download and returns to invoice section', function () {
    $component = Livewire::test(CreateInvoice::class)
        ->set('customer_name', 'Dar Logistics Hub')
        ->set('billing_address', 'Plot 99 Bandari Rd, Kurasini')
        ->set('issue_date', '2026-09-03')
        ->set('due_date', '2026-09-17')
        ->set('items', [
            [
                'tyre_product_id' => null,
                'item_description' => 'Bridgestone 315/80R22.5 R168',
                'quantity' => 8,
                'unit_label' => 'tyres',
                'unit_price' => 800000,
                'amount' => 6400000,
            ],
        ])
        ->call('downloadPdf');

    $invoice = Invoice::where('customer_name', 'Dar Logistics Hub')->first();
    expect($invoice)->not->toBeNull();
    $component->assertFileDownloaded("Anagkazo_Invoice_{$invoice->invoice_number}.pdf");

    // Verify PDF direct download endpoint
    $response = $this->get(route('invoices.download', $invoice));
    $response->assertStatus(200);
    $response->assertHeader('content-type', 'application/pdf');
    expect($response->headers->get('content-disposition'))->toContain('Anagkazo_Invoice_');

    // Verify public WhatsApp download route
    $publicResponse = $this->get(route('invoices.public-download', $invoice));
    $publicResponse->assertStatus(200);
    $publicResponse->assertHeader('content-type', 'application/pdf');
});

test('selected payment methods are preserved and retained across invoice creation and model retrieval', function () {
    $pm = PaymentMethod::firstOrCreate(
        ['name' => 'CRDB Bank Test'],
        [
            'type' => 'bank_transfer',
            'account_number_or_till' => '01509999999',
            'account_name' => 'Anagkazo Tyres Test',
            'branch' => 'Kariakoo',
            'is_active' => true,
        ]
    );

    $chosenIds = [$pm->id];

    $component = Livewire::test(CreateInvoice::class)
        ->set('customer_name', 'Msimbazi Heavy Fleet')
        ->set('billing_address', 'Kariakoo Uhuru St')
        ->set('issue_date', '2026-09-03')
        ->set('due_date', '2026-09-17')
        ->set('selected_payment_method_ids', $chosenIds)
        ->set('items', [
            [
                'tyre_product_id' => null,
                'item_description' => 'Triangle 315/80R22.5',
                'quantity' => 2,
                'unit_label' => 'tyres',
                'unit_price' => 700000,
                'amount' => 1400000,
            ],
        ])
        ->call('saveInvoice', 'issued');

    $invoice = Invoice::where('customer_name', 'Msimbazi Heavy Fleet')->first();
    expect($invoice)->not->toBeNull();
    expect($invoice->selected_payment_method_ids)->toEqual($chosenIds);
    expect($invoice->payment_methods_list->pluck('id')->toArray())->toEqual($chosenIds);

    $pdfHtml = view('invoices.pdf', compact('invoice'))->render();
    expect($pdfHtml)->toContain('CRDB Bank Test');
    expect($pdfHtml)->toContain('01509999999');
});

test('final total updates dynamically when changing quantity, price, discount, and tax mode', function () {
    $tbr = TyreProduct::first();

    $component = Livewire::test(CreateInvoice::class)
        ->call('setTaxType', 'exclusive')
        ->set('items', [
            [
                'tyre_product_id' => $tbr?->id,
                'item_description' => 'Test Tyre',
                'quantity' => 2,
                'unit_label' => 'tyres',
                'unit_price' => 500000,
                'amount' => 1000000,
            ],
        ])
        ->set('discount_tzs', 0);

    // Initial Exclusive total: 2 * 500,000 = 1,000,000
    $component->assertSet('total_amount_tzs', 1000000.0);

    // 1. Change quantity to 4 -> total must dynamically become 2,000,000
    $component->set('items.0.quantity', 4)
        ->assertSet('subtotal_tzs', 2000000.0)
        ->assertSet('total_amount_tzs', 2000000.0);

    // 2. Change unit price to 600,000 -> 4 * 600,000 = 2,400,000
    $component->set('items.0.unit_price', 600000)
        ->assertSet('subtotal_tzs', 2400000.0)
        ->assertSet('total_amount_tzs', 2400000.0);

    // 3. Add discount of 400,000 -> total dynamically becomes 2,000,000
    $component->set('discount_tzs', 400000)
        ->assertSet('total_amount_tzs', 2000000.0);

    // 4. Switch to TAX Inclusive with 18% -> Net: 2,000,000, Tax (18%): 360,000, Total: 2,360,000
    $component->call('setTaxType', 'inclusive')
        ->assertSet('tax_amount_tzs', 360000.0)
        ->assertSet('total_amount_tzs', 2360000.0);

    // 5. Change discount to 0 -> Net: 2,400,000, Tax (18%): 432,000, Total: 2,832,000
    $component->set('discount_tzs', 0)
        ->assertSet('tax_amount_tzs', 432000.0)
        ->assertSet('total_amount_tzs', 2832000.0);
});

test('delete confirmation modal prompts before deletion across products, Payments, and invoices', function () {
    // 1. Product Delete Modal
    $product = TyreProduct::create([
        'sku' => 'TYR-MODAL-DEL',
        'brand' => 'Apollo',
        'pattern' => 'Endurace',
        'size' => '295/80R22.5',
        'category' => 'truck_bus_radial',
        'unit_price_tzs' => 600000,
        'wholesale_price_tzs' => 550000,
        'stock_quantity' => 10,
        'reorder_threshold' => 3,
        'is_active' => true,
    ]);

    Livewire::test(ProductManager::class)
        ->call('confirmDelete', $product->id)
        ->assertSet('showDeleteModal', true)
        ->assertSet('deletingProductId', $product->id)
        ->call('deleteProduct')
        ->assertSet('showDeleteModal', false);

    expect(TyreProduct::where('sku', 'TYR-MODAL-DEL')->exists())->toBeFalse();

    // 2. Payment Delete Modal
    $pm = PaymentMethod::create([
        'name' => 'Amana Bank Kariakoo',
        'type' => 'bank_transfer',
        'bank_name' => 'Amana Bank',
        'account_number_or_till' => '100293847',
        'account_name' => 'Anagkazo Tyres Ltd',
        'branch' => 'Uhuru St',
        'is_active' => true,
    ]);

    Livewire::test(PaymentMethodsManager::class)
        ->call('confirmDelete', $pm->id)
        ->assertSet('showDeleteModal', true)
        ->assertSet('deletingMethodId', $pm->id)
        ->call('deleteMethod')
        ->assertSet('showDeleteModal', false);

    expect(PaymentMethod::where('id', $pm->id)->exists())->toBeFalse();

    // 3. Invoice Delete Modal
    $inv = Invoice::create([
        'invoice_number' => 'INV-MODAL-DEL',
        'customer_name' => 'Test Delete Client',
        'issue_date' => now(),
        'due_date' => now()->addDays(7),
        'payment_terms' => 'Cash',
        'subtotal_tzs' => 100000,
        'total_amount_tzs' => 100000,
        'status' => 'draft',
    ]);

    Livewire::test(InvoiceList::class)
        ->call('confirmDelete', $inv->id)
        ->assertSet('showDeleteModal', true)
        ->assertSet('deletingInvoiceId', $inv->id)
        ->call('deleteInvoice')
        ->assertSet('showDeleteModal', false);

    expect(Invoice::where('id', $inv->id)->exists())->toBeFalse();
});

test('reports section supports flexible custom date and period filtering', function () {
    // Create an old invoice from 6 months ago
    Invoice::create([
        'invoice_number' => 'INV-OLD-2025',
        'customer_name' => 'Past Year Client',
        'issue_date' => now()->subMonths(6)->format('Y-m-d'),
        'due_date' => now()->subMonths(6)->addDays(7)->format('Y-m-d'),
        'payment_terms' => 'Cash',
        'subtotal_tzs' => 5000000,
        'tax_rate_percent' => 0,
        'tax_amount_tzs' => 0,
        'total_amount_tzs' => 5000000,
        'amount_paid_tzs' => 5000000,
        'status' => 'paid',
    ]);

    // Create a current invoice
    Invoice::create([
        'invoice_number' => 'INV-CURRENT-2026',
        'customer_name' => 'Today Client',
        'issue_date' => now()->format('Y-m-d'),
        'due_date' => now()->addDays(7)->format('Y-m-d'),
        'payment_terms' => 'Cash',
        'subtotal_tzs' => 1000000,
        'tax_rate_percent' => 0,
        'tax_amount_tzs' => 0,
        'total_amount_tzs' => 1000000,
        'amount_paid_tzs' => 1000000,
        'status' => 'paid',
    ]);

    $customerA = Customer::create(['name' => 'Customer A', 'customer_type' => 'retail', 'phone' => '0711111111', 'billing_address' => 'Kariakoo Uhuru St']);
    $customerB = Customer::create(['name' => 'Customer B', 'customer_type' => 'corporate_ngo', 'phone' => '0722222222', 'billing_address' => 'Nyerere Rd']);

    $invA = Invoice::create([
        'invoice_number' => 'INV-REP-01',
        'customer_id' => $customerA->id,
        'customer_name' => $customerA->name,
        'issue_date' => now()->format('Y-m-d'),
        'due_date' => now()->addDays(7)->format('Y-m-d'),
        'total_amount_tzs' => 500000,
        'amount_paid_tzs' => 500000,
        'payment_status' => 'paid',
        'status' => 'issued',
    ]);

    $invB = Invoice::create([
        'invoice_number' => 'INV-REP-02',
        'customer_id' => $customerB->id,
        'customer_name' => $customerB->name,
        'issue_date' => now()->subMonths(2)->format('Y-m-d'),
        'due_date' => now()->subMonths(2)->addDays(14)->format('Y-m-d'),
        'total_amount_tzs' => 1200000,
        'amount_paid_tzs' => 0,
        'payment_status' => 'overdue',
        'status' => 'issued',
    ]);

    Livewire::test(ReportsIndex::class)
        ->call('setPeriod', 'today')
        ->assertSet('period', 'today')
        ->assertViewHas('totalInvoiced', 1500000.0)
        ->assertViewHas('invoiceCount', 2)
        ->call('setPeriod', 'all_time')
        ->assertSet('period', 'all_time')
        ->assertViewHas('totalInvoiced', fn ($val) => $val >= 2700000.0)
        ->assertViewHas('invoiceCount', fn ($val) => $val >= 3)
        ->call('selectCustomer', $customerA->id)
        ->assertSet('selectedCustomerId', $customerA->id)
        ->assertViewHas('totalInvoiced', 500000.0)
        ->assertViewHas('invoiceCount', 1)
        ->call('selectCustomer', null)
        ->assertSet('selectedCustomerId', null)
        ->call('setInvoiceStatusFilter', 'overdue')
        ->assertSet('invoiceStatusFilter', 'overdue')
        ->assertViewHas('reportInvoicesList', function ($list) {
            return $list->where('invoice_number', 'INV-REP-02')->count() === 1 && $list->every(fn ($i) => $i->payment_status === 'overdue');
        })
        ->call('setInvoiceStatusFilter', 'paid')
        ->assertSet('invoiceStatusFilter', 'paid')
        ->assertViewHas('reportInvoicesList', function ($list) {
            return $list->where('invoice_number', 'INV-REP-01')->count() === 1 && $list->every(fn ($i) => $i->payment_status === 'paid');
        })
        ->call('resetFilters')
        ->assertSet('period', 'all_time')
        ->assertSet('selectedCustomerId', null)
        ->assertSet('invoiceStatusFilter', 'all');
});

test('invoice builder supports custom payment terms and issuer name and phone', function () {
    $component = Livewire::test(CreateInvoice::class)
        ->assertSee('3 Days')
        ->assertSee('7 Days')
        ->assertSee('14 Days')
        ->assertSee('30 Days')
        ->assertSee('Cash on Delivery')
        ->assertSee('Counter Cash')
        ->assertSee('50% Advance, 50% on Delivery')
        ->set('payment_terms', '3 Days')
        ->assertSet('due_date', now()->addDays(3)->format('Y-m-d'))
        ->set('payment_terms', '30 Days')
        ->assertSet('due_date', now()->addDays(30)->format('Y-m-d'))
        ->set('payment_terms', 'Cash on Delivery')
        ->assertSet('due_date', now()->format('Y-m-d'))
        ->set('issuer_name', 'Mussa Juma')
        ->set('issuer_phone', '+255 788 123 456')
        ->call('saveInvoice')
        ->assertHasNoErrors();

    $invoice = Invoice::where('issuer_name', 'Mussa Juma')->first();
    expect($invoice)->not->toBeNull();
    expect($invoice->issuer_phone)->toBe('+255 788 123 456');
    expect($invoice->payment_terms)->toBe('Cash on Delivery');
});

test('customer directory supports full CRUD operations with delete confirmation modal', function () {
    // 1. Render Customer Directory
    $response = $this->get(route('customers.index'));
    $response->assertStatus(200);
    $response->assertSee('Customer Directory');
    $response->assertSee('Retail');
    $response->assertSee('Corporate / NGOs');
    $response->assertSee('Government');

    // 2. Create Customer via Livewire
    Livewire::test(CustomerManager::class)
        ->call('openCreateModal')
        ->assertSet('showCustomerModal', true)
        ->set('name', 'Azam Marine Fleet Ltd')
        ->set('customer_type', 'corporate_ngo')
        ->set('contact_person', 'Rashid Bakari')
        ->set('phone', '+255 714 998 877')
        ->set('email', 'fleet@azammarine.com')
        ->set('tin_number', '109-883-221')
        ->set('vrn_number', '40-038291-B')
        ->set('billing_address', 'Ferry Terminal, Kivukoni, Dar es Salaam')
        ->call('saveCustomer')
        ->assertHasNoErrors()
        ->assertSet('showCustomerModal', false);

    $customer = Customer::where('name', 'Azam Marine Fleet Ltd')->first();
    expect($customer)->not->toBeNull();
    expect($customer->customer_type)->toBe('corporate_ngo');

    // 3. Edit Customer
    Livewire::test(CustomerManager::class)
        ->call('editCustomer', $customer->id)
        ->assertSet('showCustomerModal', true)
        ->assertSet('isEditing', true)
        ->assertSet('name', 'Azam Marine Fleet Ltd')
        ->set('name', 'Azam Marine Logistics & Fleet Ltd')
        ->call('saveCustomer')
        ->assertHasNoErrors()
        ->assertSet('showCustomerModal', false);

    expect($customer->fresh()->name)->toBe('Azam Marine Logistics & Fleet Ltd');

    // 4. Delete Customer with Confirmation Modal
    Livewire::test(CustomerManager::class)
        ->call('confirmDelete', $customer->id)
        ->assertSet('showDeleteModal', true)
        ->assertSet('deletingCustomerId', $customer->id)
        ->call('deleteCustomer')
        ->assertSet('showDeleteModal', false);

    expect(Customer::where('id', $customer->id)->exists())->toBeFalse();
});

test('reports section includes customer analytics and segment metrics', function () {
    Livewire::test(ReportsIndex::class)
        ->assertSee('Customer Financial Intelligence')
        ->assertSee('Segment Revenue Contribution')
        ->assertSee('Total Client Accounts')
        ->assertSee('Open Customer Directory');
});

test('invoices display Managing Director Joseph Matemba signature section and official seal stamp', function () {
    // 1. In Create Invoice live preview
    Livewire::test(CreateInvoice::class)
        ->assertSee('Joseph Matemba')
        ->assertSee('Managing Director')
        ->assertSee('font-pinyon-script')
        ->assertSee('official-stamp.png');

    // 2. In Invoice Show View
    $invoice = Invoice::first();
    if (! $invoice) {
        $invoice = Invoice::create([
            'invoice_number' => 'INV-2026-TEST',
            'customer_name' => 'Bakhresa Group',
            'customer_phone' => '+255 777 000 111',
            'subtotal_tzs' => 1000000,
            'total_amount_tzs' => 1000000,
            'amount_paid_tzs' => 1000000,
            'payment_status' => 'paid',
            'tax_type' => 'exclusive',
            'payment_terms' => 'Cash on Delivery',
            'issue_date' => now()->toDateString(),
            'due_date' => now()->toDateString(),
        ]);
    }

    $responseShow = $this->get(route('invoices.show', $invoice));
    $responseShow->assertStatus(200);
    $responseShow->assertSee('Joseph Matemba');
    $responseShow->assertSee('Managing Director');
    $responseShow->assertSee('official-stamp.png');

    // 3. In Printable Invoice
    $responsePrint = $this->get(route('invoices.print', $invoice));
    $responsePrint->assertStatus(200);
    $responsePrint->assertSee('Joseph Matemba');
    $responsePrint->assertSee('Managing Director');
    $responsePrint->assertSee('official-stamp.png');
    $responsePrint->assertSee('Close & Return', false);
    $responsePrint->assertSee('Print / Save PDF');
});

test('global sticky navigation headers include wire:navigate for smooth animated section transitions', function () {
    $response = $this->get(route('dashboard'));
    $response->assertStatus(200);
    $response->assertSee('wire:navigate', false);
    $response->assertSee('app.js');

    $responseInvoice = $this->get(route('invoices.create'));
    $responseInvoice->assertStatus(200);
    $responseInvoice->assertSee('wire:navigate', false);
});

test('invoice status calculations, scopes, and customer status breakdowns work seamlessly', function () {
    $customer = Customer::create([
        'name' => 'Kilimanjaro Express Fleet',
        'contact_person' => 'Ibrahim Lyimo',
        'phone' => '+255 755 112 233',
        'customer_type' => 'corporate_ngo',
        'billing_address' => 'Moshi / Dar Highway',
    ]);

    // 1. Paid invoice
    $paidInvoice = Invoice::create([
        'invoice_number' => 'INV-TEST-PAID-01',
        'customer_id' => $customer->id,
        'customer_name' => $customer->name,
        'subtotal_tzs' => 4000000,
        'total_amount_tzs' => 4000000,
        'amount_paid_tzs' => 4000000,
        'status' => 'paid',
        'issue_date' => now()->subDays(10)->toDateString(),
        'due_date' => now()->subDays(3)->toDateString(),
    ]);

    // 2. Pending invoice (within terms)
    $pendingInvoice = Invoice::create([
        'invoice_number' => 'INV-TEST-PEND-01',
        'customer_id' => $customer->id,
        'customer_name' => $customer->name,
        'subtotal_tzs' => 5000000,
        'total_amount_tzs' => 5000000,
        'amount_paid_tzs' => 1000000,
        'status' => 'issued',
        'issue_date' => now()->subDays(2)->toDateString(),
        'due_date' => now()->addDays(12)->toDateString(),
    ]);

    // 3. Overdue invoice
    $overdueInvoice = Invoice::create([
        'invoice_number' => 'INV-TEST-OVER-01',
        'customer_id' => $customer->id,
        'customer_name' => $customer->name,
        'subtotal_tzs' => 8000000,
        'total_amount_tzs' => 8000000,
        'amount_paid_tzs' => 0,
        'status' => 'issued',
        'issue_date' => now()->subDays(30)->toDateString(),
        'due_date' => now()->subDays(15)->toDateString(),
    ]);

    // Verify computed status accessors
    expect($paidInvoice->payment_status)->toBe('paid');
    expect($pendingInvoice->payment_status)->toBe('partial');
    expect($overdueInvoice->payment_status)->toBe('overdue');
    expect($overdueInvoice->days_overdue)->toBeGreaterThanOrEqual(15);
    expect($pendingInvoice->balance_tzs)->toBe(4000000.0);
    expect($overdueInvoice->balance_tzs)->toBe(8000000.0);

    // Verify Customer aggregations
    $freshCustomer = $customer->fresh();
    expect($freshCustomer->paid_invoices_count)->toBe(1);
    expect($freshCustomer->pending_invoices_count)->toBe(1);
    expect($freshCustomer->overdue_invoices_count)->toBe(1);
    expect($freshCustomer->has_overdue_invoices)->toBeTrue();
    expect($freshCustomer->balance_tzs)->toBe(12000000.0);

    // Verify Invoices List view contains status badges
    $responseInvoices = $this->get(route('invoices.index'));
    $responseInvoices->assertStatus(200);
    $responseInvoices->assertSee('PAID');
    $responseInvoices->assertSee('OVERDUE');

    // Verify Reports view with customer drilldown
    $responseReports = $this->get(route('reports.index'));
    $responseReports->assertStatus(200);
    $responseReports->assertSee('Kilimanjaro Express Fleet');
    $responseReports->assertSee('Customer Accounts', false);
});

test('reports section calculates and renders Turnover, Receivables Aging, and Invoice Settlement Donut with live data', function () {
    $response = $this->get(route('reports.index'));
    $response->assertStatus(200);

    // Section 1 Header
    $response->assertSee('Invoices, Turnover', false);
    $response->assertSee('Receivables Aging', false);
    $response->assertSee('Monthly Revenue Trajectory', false);
    $response->assertSee('Invoice Settlement Status', false);
    $response->assertSee('Accounts Receivable Aging', false);
    $response->assertSee('Invoices Register (Detailed Ledger)', false);

    // Aging Brackets
    $response->assertSee('0 - 7 Days (Current)', false);
    $response->assertSee('8 - 14 Days (Due Soon)', false);
    $response->assertSee('15 - 30 Days (Follow-up)', false);
    $response->assertSee('30+ Days (Critical Overdue)', false);
});

test('products section allows user to set custom category manually', function () {
    $customCategory = 'Heavy Earthmover & OTR Mining';
    $customSku = 'TYR-CAT-OTR-01';

    Livewire::test(ProductManager::class)
        ->set('brand', 'Triangle')
        ->set('pattern', 'TB516 Radial Earthmover')
        ->set('size', '23.5R25')
        ->set('category', $customCategory)
        ->set('sku', $customSku)
        ->set('unit_price_tzs', 4500000)
        ->set('wholesale_price_tzs', 4200000)
        ->set('stock_quantity', 12)
        ->set('reorder_threshold', 3)
        ->call('saveProduct');

    $product = TyreProduct::where('sku', $customSku)->first();
    expect($product)->not->toBeNull();
    expect($product->category)->toBe($customCategory);

    // View in products page
    $response = $this->get(route('products.index'));
    $response->assertStatus(200);
    $response->assertSee($customCategory);
});

test('payment methods manager supports setting and saving logo url and displays logo on invoices', function () {
    $logoUrl = 'https://images.unsplash.com/photo-1628348068343-c6a848d2b6dd?w=200&auto=format&fit=crop&q=80';

    Livewire::test(PaymentMethodsManager::class)
        ->set('name', 'Absa Bank Tanzania (Kariakoo)')
        ->set('type', 'bank_transfer')
        ->set('bank_name', 'Absa Bank Plc')
        ->set('account_number_or_till', '0309988221')
        ->set('account_name', 'Anagkazo Tyres Ltd')
        ->set('branch', 'Msimbazi Street')
        ->set('logo_url', $logoUrl)
        ->call('createPaymentMethod');

    $pm = PaymentMethod::where('account_number_or_till', '0309988221')->first();
    expect($pm)->not->toBeNull();
    expect($pm->logo_url)->toBe($logoUrl);

    // Verify logo renders on payments page and invoices
    $response = $this->get(route('payment-methods.index'));
    $response->assertStatus(200);
    $response->assertSee($logoUrl);
});

test('customer tier classification correctly identifies Premium (>100M), Medium (50M-99M), and Standard (<50M) customers', function () {
    // 1. Premium Customer (> 100M)
    $premiumCust = Customer::create([
        'name' => 'Tanzania National Logistics Ltd',
        'contact_person' => 'Juma Khamis',
        'phone' => '+255 754 112 233',
        'billing_address' => 'Plot 10, Port Access Road, Dar es Salaam',
        'customer_type' => 'corporate_ngo',
    ]);

    Invoice::create([
        'invoice_number' => 'INV-TIER-PREM-01',
        'customer_id' => $premiumCust->id,
        'customer_name' => $premiumCust->name,
        'subtotal_tzs' => 120000000,
        'total_amount_tzs' => 120000000,
        'amount_paid_tzs' => 120000000,
        'status' => 'paid',
        'issue_date' => now()->toDateString(),
        'due_date' => now()->addDays(14)->toDateString(),
    ]);

    // 2. Medium Customer (50M - 99M)
    $mediumCust = Customer::create([
        'name' => 'Kariakoo Express Buses',
        'contact_person' => 'Rashid Mwinyi',
        'phone' => '+255 713 445 566',
        'billing_address' => 'Uhuru Street, Kariakoo',
        'customer_type' => 'corporate_ngo',
    ]);

    Invoice::create([
        'invoice_number' => 'INV-TIER-MED-01',
        'customer_id' => $mediumCust->id,
        'customer_name' => $mediumCust->name,
        'subtotal_tzs' => 65000000,
        'total_amount_tzs' => 65000000,
        'amount_paid_tzs' => 30000000,
        'status' => 'issued',
        'issue_date' => now()->toDateString(),
        'due_date' => now()->addDays(14)->toDateString(),
    ]);

    // 3. Standard Customer (< 50M)
    $standardCust = Customer::create([
        'name' => 'City Auto Repair Shop',
        'contact_person' => 'Peter John',
        'phone' => '+255 788 778 899',
        'billing_address' => 'Swahili Street, Kariakoo',
        'customer_type' => 'retail',
    ]);

    Invoice::create([
        'invoice_number' => 'INV-TIER-STD-01',
        'customer_id' => $standardCust->id,
        'customer_name' => $standardCust->name,
        'subtotal_tzs' => 12000000,
        'total_amount_tzs' => 12000000,
        'amount_paid_tzs' => 12000000,
        'status' => 'paid',
        'issue_date' => now()->toDateString(),
        'due_date' => now()->addDays(14)->toDateString(),
    ]);

    expect($premiumCust->fresh()->tier)->toBe('premium');
    expect($premiumCust->fresh()->is_premium)->toBeTrue();
    expect($premiumCust->fresh()->tier_label)->toBe('Premium Customer');

    expect($mediumCust->fresh()->tier)->toBe('medium');
    expect($mediumCust->fresh()->tier_label)->toBe('Medium Customer');

    expect($standardCust->fresh()->tier)->toBe('standard');
    expect($standardCust->fresh()->tier_label)->toBe('Standard Customer');

    // Customer directory displays tier badges
    $response = $this->get(route('customers.index'));
    $response->assertStatus(200);
    $response->assertSee('Premium');

    // Show invoice displays Premium Customer badge
    $premInvoice = Invoice::where('invoice_number', 'INV-TIER-PREM-01')->first();
    $responseInv = $this->get(route('invoices.show', $premInvoice));
    $responseInv->assertStatus(200);
    $responseInv->assertSee('Premium Customer');
});

test('reports section displays in-stocks and out-stocks inventory movement', function () {
    $response = $this->get(route('reports.index'));
    $response->assertStatus(200);

    $response->assertSee('In-Stocks (Received');
    $response->assertSee('Out-Stocks (Dispatched');
    $response->assertSee('Depot Stock Movement by Profile');
    $response->assertSee('Turnover Rate');
});

test('customer manager validates emails strictly', function () {
    // Valid email succeeds
    Livewire::test(CustomerManager::class)
        ->set('name', 'Valid Customer Ltd')
        ->set('billing_address', 'Plot 20 Kariakoo')
        ->set('email', 'info@mangi-autoparts.co.tz')
        ->set('customer_type', 'corporate_ngo')
        ->call('saveCustomer')
        ->assertHasNoErrors(['email']);

    expect(Customer::where('email', 'info@mangi-autoparts.co.tz')->exists())->toBeTrue();

    // Invalid email formats fail
    Livewire::test(CustomerManager::class)
        ->set('name', 'Invalid Email Cust')
        ->set('billing_address', 'Kariakoo DSM')
        ->set('email', 'not-an-email')
        ->call('saveCustomer')
        ->assertHasErrors(['email']);

    Livewire::test(CustomerManager::class)
        ->set('name', 'Invalid Domain Cust')
        ->set('billing_address', 'Kariakoo DSM')
        ->set('email', 'user@domain')
        ->call('saveCustomer')
        ->assertHasErrors(['email']);

    Livewire::test(CustomerManager::class)
        ->set('name', 'Missing Local Cust')
        ->set('billing_address', 'Kariakoo DSM')
        ->set('email', '@domain.com')
        ->call('saveCustomer')
        ->assertHasErrors(['email']);
});

test('customer manager filters by premium medium and standard tiers', function () {
    // Create Premium customer (> 100M)
    $premCust = Customer::create([
        'name' => 'Mega Logistics Ltd',
        'billing_address' => 'Nyerere Road, DSM',
        'customer_type' => 'corporate_ngo',
    ]);
    Invoice::create([
        'invoice_number' => 'INV-PREM-001',
        'customer_id' => $premCust->id,
        'customer_name' => $premCust->name,
        'billing_address' => $premCust->billing_address,
        'issue_date' => now()->format('Y-m-d'),
        'due_date' => now()->addDays(14)->format('Y-m-d'),
        'status' => 'paid',
        'total_amount_tzs' => 120000000,
        'amount_paid_tzs' => 120000000,
    ]);

    // Create Medium customer (50M - 99.9M)
    $medCust = Customer::create([
        'name' => 'City Bus Express',
        'billing_address' => 'Shekilango, DSM',
        'customer_type' => 'corporate_ngo',
    ]);
    Invoice::create([
        'invoice_number' => 'INV-MED-001',
        'customer_id' => $medCust->id,
        'customer_name' => $medCust->name,
        'billing_address' => $medCust->billing_address,
        'issue_date' => now()->format('Y-m-d'),
        'due_date' => now()->addDays(14)->format('Y-m-d'),
        'status' => 'paid',
        'total_amount_tzs' => 65000000,
        'amount_paid_tzs' => 65000000,
    ]);

    // Create Standard customer (< 50M)
    $stdCust = Customer::create([
        'name' => 'Local Workshop',
        'billing_address' => 'Kariakoo, DSM',
        'customer_type' => 'retail',
    ]);
    Invoice::create([
        'invoice_number' => 'INV-STD-001',
        'customer_id' => $stdCust->id,
        'customer_name' => $stdCust->name,
        'billing_address' => $stdCust->billing_address,
        'issue_date' => now()->format('Y-m-d'),
        'due_date' => now()->addDays(14)->format('Y-m-d'),
        'status' => 'paid',
        'total_amount_tzs' => 15000000,
        'amount_paid_tzs' => 15000000,
    ]);

    // Test filtering by premium
    Livewire::test(CustomerManager::class)
        ->set('tierFilter', 'premium')
        ->assertSee('Mega Logistics Ltd')
        ->assertDontSee('City Bus Express')
        ->assertDontSee('Local Workshop');

    // Test filtering by medium
    Livewire::test(CustomerManager::class)
        ->set('tierFilter', 'medium')
        ->assertSee('City Bus Express')
        ->assertDontSee('Mega Logistics Ltd')
        ->assertDontSee('Local Workshop');

    // Test filtering by standard
    Livewire::test(CustomerManager::class)
        ->set('tierFilter', 'standard')
        ->assertSee('Local Workshop')
        ->assertDontSee('Mega Logistics Ltd')
        ->assertDontSee('City Bus Express');
});

test('page footer renders across main pages with depot details', function () {
    $response = $this->get(route('dashboard'));
    $response->assertStatus(200);
    $response->assertSee('ANAGKAZO');
    $response->assertSee('Msimbazi & Sikukuu Street', false);
    $response->assertSee('+255 754 889 912');
    $response->assertSee('All rights reserved');
    $response->assertSee('Developed by');
    $response->assertSee('DesignHub');
    $response->assertSee('https://www.designhub.co.tz', false);

    $custResponse = $this->get(route('customers.index'));
    $custResponse->assertStatus(200);
    $custResponse->assertSee('Msimbazi & Sikukuu Street', false);
    $custResponse->assertSee('https://www.designhub.co.tz', false);
});

