<?php

use App\Livewire\Customers\CustomerManager;
use App\Livewire\Dashboard\Overview;
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
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;

beforeEach(function () {
    // Ensure Admin and Staff exist
    $this->adminUser = User::updateOrCreate(
        ['email' => 'admin@anagkazo.co.tz'],
        [
            'name' => 'Admin Superuser',
            'role' => 'admin',
            'password' => bcrypt('superuser@2026'),
        ]
    );

    $this->staffUser = User::updateOrCreate(
        ['email' => 'staff@anagkazo.co.tz'],
        [
            'name' => 'Staff Operator',
            'role' => 'staff',
            'password' => bcrypt('staffwaanagkazo@2026'),
        ]
    );
});

test('staff user can view customers directory but cannot create, edit, or delete customers', function () {
    $customer = Customer::create([
        'name' => 'Kariakoo Transit Hub',
        'contact_person' => 'Juma Hamisi',
        'phone' => '0712345678',
        'email' => 'juma@transit.co.tz',
        'billing_address' => 'Plot 40 Msimbazi Street, Kariakoo',
        'customer_type' => 'corporate_ngo',
    ]);

    // As Staff: can view directory
    $response = $this->actingAs($this->staffUser)->get(route('customers.index'));
    $response->assertStatus(200);
    $response->assertSee('Kariakoo Transit Hub');
    $response->assertDontSee('Register New Customer');

    // Livewire CRUD attempts as Staff are blocked
    Livewire::actingAs($this->staffUser)
        ->test(CustomerManager::class)
        ->call('openCreateModal')
        ->assertSet('showCustomerModal', false);

    Livewire::actingAs($this->staffUser)
        ->test(CustomerManager::class)
        ->call('editCustomer', $customer->id)
        ->assertSet('showCustomerModal', false);

    Livewire::actingAs($this->staffUser)
        ->test(CustomerManager::class)
        ->set('name', 'Hacked Customer')
        ->set('billing_address', 'Dar')
        ->call('saveCustomer')
        ->assertForbidden();

    Livewire::actingAs($this->staffUser)
        ->test(CustomerManager::class)
        ->call('deleteCustomer', $customer->id)
        ->assertForbidden();

    expect(Customer::where('id', $customer->id)->exists())->toBeTrue();
});

test('admin user has full CRUD permissions on customers', function () {
    $response = $this->actingAs($this->adminUser)->get(route('customers.index'));
    $response->assertStatus(200);
    $response->assertSee('Register New Customer');

    Livewire::actingAs($this->adminUser)
        ->test(CustomerManager::class)
        ->set('name', 'Admin Created Client')
        ->set('billing_address', 'Uhuru Street, Kariakoo')
        ->set('email', 'adminclient@co.tz')
        ->set('customer_type', 'retail')
        ->call('saveCustomer')
        ->assertHasNoErrors();

    $created = Customer::where('email', 'adminclient@co.tz')->first();
    expect($created)->not->toBeNull();

    Livewire::actingAs($this->adminUser)
        ->test(CustomerManager::class)
        ->call('editCustomer', $created->id)
        ->assertSet('showCustomerModal', true)
        ->set('name', 'Admin Updated Client')
        ->call('saveCustomer');

    expect($created->fresh()->name)->toBe('Admin Updated Client');
});

test('staff user can view products stock but cannot create, edit, or delete products or adjust stock', function () {
    $product = TyreProduct::create([
        'brand' => 'Triangle',
        'pattern' => 'TR668 Heavy',
        'size' => '315/80R22.5',
        'category' => 'Truck & Bus Radial (TBR)',
        'sku' => 'TYR-TEST-001',
        'unit_price_tzs' => 750000,
        'wholesale_price_tzs' => 700000,
        'stock_quantity' => 20,
        'reorder_threshold' => 5,
        'is_active' => true,
    ]);

    // View products list as staff
    $response = $this->actingAs($this->staffUser)->get(route('products.index'));
    $response->assertStatus(200);
    $response->assertSee('315/80R22.5');
    $response->assertDontSee('Add new tyre');

    // Livewire actions as Staff are blocked
    Livewire::actingAs($this->staffUser)
        ->test(ProductManager::class)
        ->call('openCreateModal')
        ->assertSet('showProductModal', false);

    Livewire::actingAs($this->staffUser)
        ->test(ProductManager::class)
        ->call('openEditModal', $product->id)
        ->assertSet('showProductModal', false);

    Livewire::actingAs($this->staffUser)
        ->test(ProductManager::class)
        ->set('brand', 'FakeBrand')
        ->set('pattern', 'FakePattern')
        ->set('size', '12R22.5')
        ->set('category', 'Truck & Bus Radial (TBR)')
        ->set('sku', 'TYR-FAKE-999')
        ->set('unit_price_tzs', 500000)
        ->set('stock_quantity', 10)
        ->set('reorder_threshold', 2)
        ->call('saveProduct')
        ->assertForbidden();

    Livewire::actingAs($this->staffUser)
        ->test(ProductManager::class)
        ->call('deleteProduct', $product->id)
        ->assertForbidden();

    Livewire::actingAs($this->staffUser)
        ->test(ProductManager::class)
        ->call('quickStockChange', $product->id, 5);

    Livewire::actingAs($this->staffUser)
        ->test(ProductManager::class)
        ->call('applyStockAdjustment', 'add')
        ->assertForbidden();

    expect($product->fresh()->stock_quantity)->toBe(20);
});

test('staff user can create and issue invoices but cannot add payment methods or view records', function () {
    $customer = Customer::create([
        'name' => 'Safari Express Line',
        'billing_address' => 'Morogoro Road, DSM',
        'customer_type' => 'corporate_ngo',
    ]);

    $product = TyreProduct::create([
        'brand' => 'Sailun',
        'pattern' => 'S825 Regional',
        'size' => '295/80R22.5',
        'category' => 'Truck & Bus Radial (TBR)',
        'sku' => 'TYR-TEST-002',
        'unit_price_tzs' => 600000,
        'stock_quantity' => 40,
        'reorder_threshold' => 10,
        'is_active' => true,
    ]);

    $pm = PaymentMethod::create([
        'name' => 'CRDB Bank Kariakoo',
        'type' => 'bank_transfer',
        'bank_name' => 'CRDB Bank Plc',
        'account_number_or_till' => '0150294827100',
        'account_name' => 'Anagkazo Tyres Ltd',
        'is_active' => true,
    ]);

    $response = $this->actingAs($this->staffUser)->get(route('invoices.create'));
    $response->assertStatus(200);
    $response->assertSee('Create New Invoice');
    $response->assertDontSee('Add Payment Method');

    // Staff attempting to add a payment method is forbidden
    Livewire::actingAs($this->staffUser)
        ->test(CreateInvoice::class)
        ->set('new_pm_name', 'Hacked Bank')
        ->set('new_pm_account', '12345678')
        ->call('addPaymentMethodQuickly')
        ->assertForbidden();

    // Generate and issue invoice as staff selecting existing admin payment method
    Livewire::actingAs($this->staffUser)
        ->test(CreateInvoice::class)
        ->set('customer_id', $customer->id)
        ->set('customer_name', $customer->name)
        ->set('billing_address', $customer->billing_address)
        ->set('issue_date', now()->format('Y-m-d'))
        ->set('due_date', now()->addDays(14)->format('Y-m-d'))
        ->set('selected_payment_method_ids', [$pm->id])
        ->set('items', [
            [
                'tyre_product_id' => $product->id,
                'item_description' => 'Sailun 295/80R22.5 S825',
                'quantity' => 4,
                'unit_label' => 'tyres',
                'unit_price' => 600000,
                'amount' => 2400000,
            ],
        ])
        ->set('status_message', '')
        ->call('saveInvoice', 'issued')
        ->assertHasNoErrors();

    $invoice = Invoice::where('customer_name', 'Safari Express Line')->first();
    expect($invoice)->not->toBeNull();
    expect($invoice->status)->toBe('issued');
    expect((float) $invoice->total_amount_tzs)->toBeGreaterThan(0);

    // Staff cannot view invoice records (403 Forbidden)
    $listResponse = $this->actingAs($this->staffUser)->get(route('invoices.index'));
    $listResponse->assertForbidden();

    Livewire::actingAs($this->staffUser)
        ->test(InvoiceList::class)
        ->assertForbidden();
});

test('staff user cannot access records, reports, payment settings, or financial valuation analytics', function () {
    // Records registry is forbidden for staff
    $invoicesResponse = $this->actingAs($this->staffUser)->get(route('invoices.index'));
    $invoicesResponse->assertForbidden();

    // Reports is forbidden for staff
    $reportsResponse = $this->actingAs($this->staffUser)->get(route('reports.index'));
    $reportsResponse->assertForbidden();

    // Payment settings is forbidden for staff
    $paymentResponse = $this->actingAs($this->staffUser)->get(route('payment-methods.index'));
    $paymentResponse->assertForbidden();

    // Dashboard redirects staff to invoice creation
    $dashboardResponse = $this->actingAs($this->staffUser)->get(route('dashboard'));
    $dashboardResponse->assertRedirect(route('invoices.create'));

    // Products page for staff does not show financial valuation analytics card
    $productsResponse = $this->actingAs($this->staffUser)->get(route('products.index'));
    $productsResponse->assertStatus(200);
    $productsResponse->assertDontSee('Depot Inventory Value');
});

test('admin user can access dashboard, records, reports, payment settings, and valuation analytics', function () {
    $dashboardResponse = $this->actingAs($this->adminUser)->get(route('dashboard'));
    $dashboardResponse->assertStatus(200);

    $recordsResponse = $this->actingAs($this->adminUser)->get(route('invoices.index'));
    $recordsResponse->assertStatus(200);

    $reportsResponse = $this->actingAs($this->adminUser)->get(route('reports.index'));
    $reportsResponse->assertStatus(200);

    $paymentResponse = $this->actingAs($this->adminUser)->get(route('payment-methods.index'));
    $paymentResponse->assertStatus(200);

    $productsResponse = $this->actingAs($this->adminUser)->get(route('products.index'));
    $productsResponse->assertStatus(200);
    $productsResponse->assertSee('Depot Inventory Value');

    // Admin can add payment method on invoice creation
    Livewire::actingAs($this->adminUser)
        ->test(CreateInvoice::class)
        ->set('new_pm_name', 'Admin Stanbic Bank')
        ->set('new_pm_account', '099238411')
        ->call('addPaymentMethodQuickly')
        ->assertHasNoErrors();

    expect(PaymentMethod::where('name', 'Admin Stanbic Bank')->exists())->toBeTrue();
});
