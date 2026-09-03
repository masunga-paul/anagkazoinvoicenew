<?php

use App\Livewire\Products\ProductManager;
use App\Models\TyreProduct;
use App\Models\User;
use Livewire\Livewire;

test('stock intake modal adds container shipment tyres to stock quantity', function () {
    $admin = User::factory()->create(['role' => 'admin']);
    $this->actingAs($admin);

    $product = TyreProduct::create([
        'brand' => 'Triangle',
        'pattern' => 'TR668',
        'size' => '315/80R22.5',
        'category' => 'Truck & Bus Radial (TBR)',
        'sku' => 'TYR-INTAKE-01',
        'unit_price_tzs' => 750000,
        'wholesale_price_tzs' => 700000,
        'stock_quantity' => 10,
        'reorder_threshold' => 5,
        'is_active' => true,
    ]);

    Livewire::test(ProductManager::class)
        ->call('openAdjustModal', $product->id)
        ->assertSet('showAdjustModal', true)
        ->assertSet('adjustingProductId', $product->id)
        ->set('adjustmentQuantity', 25)
        ->call('applyStockIntake')
        ->assertSet('showAdjustModal', false)
        ->assertSet('adjustingProductId', null)
        ->assertSee('Stock Intake Recorded');

    expect($product->fresh()->stock_quantity)->toBe(35);
});

test('quick stock increment and decrement buttons update tyre quantity', function () {
    $admin = User::factory()->create(['role' => 'admin']);
    $this->actingAs($admin);

    $product = TyreProduct::create([
        'brand' => 'Bridgestone',
        'pattern' => 'R168',
        'size' => '385/65R22.5',
        'category' => 'Truck & Bus Radial (TBR)',
        'sku' => 'TYR-QUICK-01',
        'unit_price_tzs' => 950000,
        'wholesale_price_tzs' => 900000,
        'stock_quantity' => 10,
        'reorder_threshold' => 5,
        'is_active' => true,
    ]);

    Livewire::test(ProductManager::class)
        ->call('quickStockChange', $product->id, 1)
        ->assertSee('Stock updated');

    expect($product->fresh()->stock_quantity)->toBe(11);

    Livewire::test(ProductManager::class)
        ->call('quickStockChange', $product->id, -1)
        ->assertSee('Stock updated');

    expect($product->fresh()->stock_quantity)->toBe(10);
});

test('product create and edit modals save and update tyre models properly', function () {
    $admin = User::factory()->create(['role' => 'admin']);
    $this->actingAs($admin);

    // Create
    Livewire::test(ProductManager::class)
        ->call('openCreateModal')
        ->assertSet('showProductModal', true)
        ->set('brand', 'Aeolus')
        ->set('pattern', 'HN08')
        ->set('size', '12.00R20')
        ->set('category', 'Truck & Bus Radial (TBR)')
        ->set('sku', 'TYR-AEOLUS-01')
        ->set('unit_price_tzs', 680000)
        ->set('stock_quantity', 40)
        ->set('reorder_threshold', 8)
        ->call('saveProduct')
        ->assertHasNoErrors()
        ->assertSet('showProductModal', false);

    $created = TyreProduct::where('sku', 'TYR-AEOLUS-01')->first();
    expect($created)->not->toBeNull();
    expect($created->brand)->toBe('Aeolus');

    // Edit
    Livewire::test(ProductManager::class)
        ->call('openEditModal', $created->id)
        ->assertSet('showProductModal', true)
        ->assertSet('isEditing', true)
        ->set('unit_price_tzs', 720000)
        ->call('saveProduct')
        ->assertHasNoErrors()
        ->assertSet('showProductModal', false);

    expect((float) $created->fresh()->unit_price_tzs)->toBe(720000.0);
});
