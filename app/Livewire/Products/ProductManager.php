<?php

namespace App\Livewire\Products;

use App\Models\TyreProduct;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.empty')]
#[Title('Depot Stock & Products - Anagkazo Tyres Kariakoo')]
class ProductManager extends Component
{
    use WithPagination;

    #[Url]
    public string $search = '';

    #[Url]
    public string $categoryFilter = 'all';

    public string $stockFilter = 'all';

    public string $viewMode = 'grid'; // 'grid' or 'table'

    // Modal state
    public bool $showProductModal = false;

    public bool $isEditing = false;

    public ?int $editingProductId = null;

    // Form fields
    public string $brand = 'Triangle';

    public string $pattern = '';

    public string $size = '';

    public string $category = 'truck_bus_radial';

    public string $sku = '';

    public float $unit_price_tzs = 750000;

    public ?float $wholesale_price_tzs = 700000;

    public int $stock_quantity = 25;

    public int $reorder_threshold = 10;

    public string $image_url = 'https://images.unsplash.com/photo-1578844251758-2f71da64c96f?w=600&auto=format&fit=crop&q=80';

    public bool $is_active = true;

    // Quick stock adjustment state
    public ?int $adjustingProductId = null;

    public int $adjustmentQuantity = 5;

    public bool $showAdjustModal = false;

    public function updatedSearch(): void
    {
        $this->resetPage();
    }

    public function updatedCategoryFilter(): void
    {
        $this->resetPage();
    }

    public function updatedStockFilter(): void
    {
        $this->resetPage();
    }

    public function openCreateModal(): void
    {
        if (auth()->user()?->isStaff()) {
            session()->flash('error', 'Staff role has view-only access to products. Contact Administrator to add new SKUs.');
            return;
        }

        $this->resetValidation();
        $this->isEditing = false;
        $this->editingProductId = null;

        $count = TyreProduct::count() + 1;
        $this->sku = 'TYR-DSM-'.str_pad((string) $count, 4, '0', STR_PAD_LEFT);
        $this->brand = 'Triangle';
        $this->pattern = 'TR668 Heavy Haulage';
        $this->size = '315/80R22.5';
        $this->category = 'Truck & Bus Radial (TBR)';
        $this->unit_price_tzs = 750000;
        $this->wholesale_price_tzs = 700000;
        $this->stock_quantity = 25;
        $this->reorder_threshold = 10;
        $this->image_url = 'https://images.unsplash.com/photo-1578844251758-2f71da64c96f?w=600&auto=format&fit=crop&q=80';
        $this->is_active = true;

        $this->showProductModal = true;
    }

    public function openEditModal(int $id): void
    {
        if (auth()->user()?->isStaff()) {
            session()->flash('error', 'Staff role has view-only access to products.');
            return;
        }

        $this->resetValidation();
        $product = TyreProduct::findOrFail($id);

        $this->isEditing = true;
        $this->editingProductId = $product->id;
        $this->brand = $product->brand;
        $this->pattern = $product->pattern;
        $this->size = $product->size;
        $this->category = $product->category;
        $this->sku = $product->sku;
        $this->unit_price_tzs = (float) $product->unit_price_tzs;
        $this->wholesale_price_tzs = (float) ($product->wholesale_price_tzs ?? 0);
        $this->stock_quantity = $product->stock_quantity;
        $this->reorder_threshold = $product->reorder_threshold;
        $this->image_url = $product->image_url ?? '';
        $this->is_active = (bool) $product->is_active;

        $this->showProductModal = true;
    }

    protected function rules(): array
    {
        $skuRule = $this->isEditing
            ? 'required|string|unique:tyre_products,sku,'.$this->editingProductId
            : 'required|string|unique:tyre_products,sku';

        return [
            'brand' => 'required|string|max:100',
            'pattern' => 'required|string|max:150',
            'size' => 'required|string|max:50',
            'category' => 'required|string|max:100',
            'sku' => $skuRule,
            'unit_price_tzs' => 'required|numeric|min:1000',
            'wholesale_price_tzs' => 'nullable|numeric|min:0',
            'stock_quantity' => 'required|integer|min:0',
            'reorder_threshold' => 'required|integer|min:1',
            'image_url' => 'nullable|url',
        ];
    }

    public function updated($propertyName): void
    {
        $this->validateOnly($propertyName);
    }

    public function saveProduct(): void
    {
        if (auth()->user()?->isStaff()) {
            session()->flash('error', 'Unauthorized action: Staff cannot modify products.');
            $this->showProductModal = false;
            return;
        }

        $this->validate();

        $data = [
            'brand' => $this->brand,
            'pattern' => $this->pattern,
            'size' => $this->size,
            'category' => $this->category,
            'sku' => strtoupper(trim($this->sku)),
            'unit_price_tzs' => $this->unit_price_tzs,
            'wholesale_price_tzs' => $this->wholesale_price_tzs ?: $this->unit_price_tzs,
            'stock_quantity' => $this->stock_quantity,
            'reorder_threshold' => $this->reorder_threshold,
            'image_url' => $this->image_url ?: 'https://images.unsplash.com/photo-1578844251758-2f71da64c96f?w=600&auto=format&fit=crop&q=80',
            'is_active' => $this->is_active,
        ];

        if ($this->isEditing && $this->editingProductId) {
            $product = TyreProduct::findOrFail($this->editingProductId);
            $product->update($data);
            session()->flash('success', "Tyre model '{$product->brand} {$product->size}' updated successfully.");
        } else {
            $product = TyreProduct::create($data);
            session()->flash('success', "New tyre SKU '{$product->sku}' added to Kariakoo depot stock.");
        }

        $this->showProductModal = false;
        $this->reset(['editingProductId', 'isEditing']);
    }

    public bool $showDeleteModal = false;

    public ?int $deletingProductId = null;

    public ?string $deletingProductName = null;

    public function confirmDelete(int $id): void
    {
        if (auth()->user()?->isStaff()) {
            session()->flash('error', 'Staff cannot delete products.');
            return;
        }

        $product = TyreProduct::findOrFail($id);
        $this->deletingProductId = $product->id;
        $this->deletingProductName = "{$product->brand} {$product->size} ({$product->sku})";
        $this->showDeleteModal = true;
    }

    public function cancelDelete(): void
    {
        $this->showDeleteModal = false;
        $this->deletingProductId = null;
        $this->deletingProductName = null;
    }

    public function deleteProduct(?int $id = null): void
    {
        if (auth()->user()?->isStaff()) {
            session()->flash('error', 'Unauthorized action: Staff cannot delete products.');
            $this->showDeleteModal = false;
            return;
        }

        $targetId = $id ?? $this->deletingProductId;
        if (! $targetId) {
            return;
        }

        $product = TyreProduct::findOrFail($targetId);
        $name = $product->brand.' '.$product->size;
        $product->delete();

        $this->showDeleteModal = false;
        $this->deletingProductId = null;
        $this->deletingProductName = null;

        session()->flash('success', "Tyre model '{$name}' deleted from depot inventory.");
    }

    public function quickStockChange(int $id, int $amount): void
    {
        if (auth()->user()?->isStaff()) {
            session()->flash('error', 'Staff cannot directly adjust stock levels.');
            return;
        }

        $product = TyreProduct::findOrFail($id);
        $newStock = max(0, $product->stock_quantity + $amount);
        $product->update(['stock_quantity' => $newStock]);

        $action = $amount > 0 ? "added {$amount} pcs to" : 'deducted '.abs($amount).' pcs from';
        session()->flash('success', "Stock updated: {$action} {$product->brand} {$product->size}. New count: {$newStock}.");
    }

    public function openAdjustModal(int $id): void
    {
        if (auth()->user()?->isStaff()) {
            session()->flash('error', 'Staff cannot perform stock adjustments.');
            return;
        }

        $this->adjustingProductId = $id;
        $this->adjustmentQuantity = 5;
        $this->showAdjustModal = true;
    }

    public function closeAdjustModal(): void
    {
        $this->showAdjustModal = false;
        $this->adjustingProductId = null;
        $this->adjustmentQuantity = 5;
    }

    public function closeProductModal(): void
    {
        $this->showProductModal = false;
        $this->editingProductId = null;
        $this->isEditing = false;
    }

    public function applyStockIntake(): void
    {
        if (auth()->user()?->isStaff()) {
            session()->flash('error', 'Unauthorized action: Staff cannot perform stock intake.');
            $this->closeAdjustModal();
            return;
        }

        if (! $this->adjustingProductId) {
            return;
        }

        $product = TyreProduct::findOrFail($this->adjustingProductId);
        $qty = max(1, (int) $this->adjustmentQuantity);

        $product->increment('stock_quantity', $qty);
        $product->refresh();

        session()->flash('success', "Stock Intake Recorded: Added +{$qty} units to {$product->brand} {$product->size} ({$product->sku}). New Stock Balance: {$product->stock_quantity} pcs.");

        $this->closeAdjustModal();
    }

    public function applyStockAdjustment(string $type = 'add'): void
    {
        if (auth()->user()?->isStaff()) {
            session()->flash('error', 'Unauthorized action: Staff cannot perform stock adjustments.');
            $this->closeAdjustModal();
            return;
        }

        if (! $this->adjustingProductId) {
            return;
        }

        $product = TyreProduct::findOrFail($this->adjustingProductId);
        $qty = max(1, abs((int) $this->adjustmentQuantity));

        if ($type === 'add') {
            $product->increment('stock_quantity', $qty);
            $product->refresh();
            session()->flash('success', "Added +{$qty} units of {$product->brand} {$product->size} to Kariakoo stock. New balance: {$product->stock_quantity}.");
        } elseif ($type === 'subtract') {
            $newQty = max(0, $product->stock_quantity - $qty);
            $product->update(['stock_quantity' => $newQty]);
            session()->flash('success', "Dispatched -{$qty} units of {$product->brand} {$product->size}. New stock: {$newQty}.");
        }

        $this->closeAdjustModal();
    }

    public function render()
    {
        $query = TyreProduct::query();

        if (! empty($this->search)) {
            $term = '%'.trim($this->search).'%';
            $query->where(function ($q) use ($term) {
                $q->where('brand', 'like', $term)
                    ->orWhere('size', 'like', $term)
                    ->orWhere('pattern', 'like', $term)
                    ->orWhere('sku', 'like', $term);
            });
        }

        if ($this->categoryFilter !== 'all') {
            $query->where('category', $this->categoryFilter);
        }

        if ($this->stockFilter === 'low_stock') {
            $query->whereColumn('stock_quantity', '<=', 'reorder_threshold')
                ->where('stock_quantity', '>', 0);
        } elseif ($this->stockFilter === 'out_of_stock') {
            $query->where('stock_quantity', '<=', 0);
        } elseif ($this->stockFilter === 'in_stock') {
            $query->whereColumn('stock_quantity', '>', 'reorder_threshold');
        }

        $products = $query->orderBy('created_at', 'desc')->paginate(12);

        // Depot metrics
        $totalSkus = TyreProduct::count();
        $totalUnits = (int) TyreProduct::sum('stock_quantity');
        $totalValuation = (float) TyreProduct::selectRaw('SUM(stock_quantity * unit_price_tzs) as val')->value('val') ?? 0;
        $lowStockCount = TyreProduct::whereColumn('stock_quantity', '<=', 'reorder_threshold')->count();
        $availableCategories = TyreProduct::distinct()->whereNotNull('category')->orderBy('category')->pluck('category')->toArray();

        return view('livewire.products.product-manager', [
            'products' => $products,
            'totalSkus' => $totalSkus,
            'totalUnits' => $totalUnits,
            'totalValuation' => $totalValuation,
            'lowStockCount' => $lowStockCount,
            'availableCategories' => $availableCategories,
        ]);
    }
}
