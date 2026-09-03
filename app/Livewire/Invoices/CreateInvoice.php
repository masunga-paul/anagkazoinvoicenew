<?php

namespace App\Livewire\Invoices;

use App\Models\Customer;
use App\Models\Invoice;
use App\Models\PaymentMethod;
use App\Models\TyreProduct;
use Carbon\Carbon;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('layouts.empty')]
#[Title('Create New Invoice - Anagkazo Tyres Kariakoo')]
class CreateInvoice extends Component
{
    public string $invoice_number = '';

    public ?int $customer_id = null;

    public string $customer_name = 'PT Nusantara Digital Solusi';

    public string $billing_address = "Jl. Jendral Sudirman No. 45 Jakarta Selatan, DKI Jakarta 12190 Indonesia\nTIN: 104-892-334 | Tel: +255 754 889 912";

    public string $issuer_name = 'Hussein Mwamba';

    public string $issuer_phone = '+255 754 889 912';

    public string $issue_date = '';

    public string $due_date = '';

    public string $payment_terms = '14 Days';

    public string $notes = 'Thank you for your business. Please complete payment before the due date. For any inquiries, feel free to contact our sales team.';

    public array $items = [];

    public int|float|string $discount_tzs = 500000;

    public int|float|string $tax_rate = 18.0; // Flexible VAT % for inclusive

    public string $tax_type = 'inclusive'; // 'inclusive' or 'exclusive'

    public float $subtotal_tzs = 0.0;

    public float $tax_amount_tzs = 0.0;

    public float $total_amount_tzs = 0.0;

    public string $status_message = '';

    public ?int $saved_invoice_id = null;

    // Payment Methods selection & quick inline add
    public array $selected_payment_method_ids = [];

    public bool $showAddPaymentModal = false;

    public string $new_pm_name = '';

    public string $new_pm_type = 'bank_transfer';

    public string $new_pm_account = '';

    public string $new_pm_account_name = 'Anagkazo Tyres Ltd';

    public string $new_pm_branch = 'Kariakoo, Dar es Salaam';

    public function mount(): void
    {
        $this->invoice_number = Invoice::generateKariakooInvoiceNumber();
        $this->issue_date = now()->format('Y-m-d');
        $this->due_date = now()->addDays(14)->format('Y-m-d');

        // Check if there are seeded customers to pick an authentic Kariakoo customer
        $firstCustomer = Customer::first();
        if ($firstCustomer) {
            $this->customer_id = $firstCustomer->id;
            $this->customer_name = $firstCustomer->name;
            $this->billing_address = $firstCustomer->billing_address."\nTIN: ".($firstCustomer->tin_number ?? 'N/A').' | Tel: '.$firstCustomer->phone;
        }

        // Initialize default items from database products
        $prod1 = TyreProduct::where('is_active', true)->first();
        $prod2 = TyreProduct::where('is_active', true)->skip(1)->first();

        $this->items = [];
        if ($prod1) {
            $this->items[] = [
                'tyre_product_id' => $prod1->id,
                'item_description' => "{$prod1->brand} {$prod1->size} {$prod1->pattern}",
                'quantity' => 10,
                'unit_label' => 'tyres',
                'unit_price' => (float) $prod1->unit_price_tzs,
                'amount' => 10 * (float) $prod1->unit_price_tzs,
            ];
        }
        if ($prod2) {
            $this->items[] = [
                'tyre_product_id' => $prod2->id,
                'item_description' => "{$prod2->brand} {$prod2->size} {$prod2->pattern}",
                'quantity' => 4,
                'unit_label' => 'tyres',
                'unit_price' => (float) $prod2->unit_price_tzs,
                'amount' => 4 * (float) $prod2->unit_price_tzs,
            ];
        }

        if (empty($this->items)) {
            $this->items[] = [
                'tyre_product_id' => null,
                'item_description' => 'Commercial Heavy Truck Radial Tyre',
                'quantity' => 1,
                'unit_label' => 'tyres',
                'unit_price' => 500000,
                'amount' => 500000,
            ];
        }

        $this->discount_tzs = 200000;
        $this->recalculateTotals();

        // Default to preferred or active payment methods
        if (session()->has('preferred_payment_methods') && is_array(session('preferred_payment_methods')) && count(session('preferred_payment_methods')) > 0) {
            $this->selected_payment_method_ids = session('preferred_payment_methods');
        } else {
            $this->selected_payment_method_ids = PaymentMethod::where('is_active', true)->pluck('id')->toArray();
        }
    }

    public function updatedSelectedPaymentMethodIds(): void
    {
        session(['preferred_payment_methods' => $this->selected_payment_method_ids]);
    }

    public function updatedCustomerId($value): void
    {
        if (! $value) {
            return;
        }

        $customer = Customer::find($value);
        if ($customer) {
            $this->customer_name = $customer->name;
            $this->billing_address = $customer->billing_address."\nTIN: ".($customer->tin_number ?? 'N/A').' | Tel: '.$customer->phone;
        }
    }

    public function updatedPaymentTerms($value = null): void
    {
        $term = $value ?? $this->payment_terms;
        $days = match ($term) {
            '3 Days', '3' => 3,
            '7 Days', '7' => 7,
            '14 Days', '14', 'Net 14' => 14,
            '30 Days', '30', 'Net 30' => 30,
            'Cash on Delivery', 'Counter Cash', '50% Advance, 50% on Delivery', 'Immediate / Cash' => 0,
            default => 14,
        };

        $base = $this->issue_date ? Carbon::parse($this->issue_date) : now();
        $this->due_date = $base->addDays($days)->format('Y-m-d');
    }

    public function updatedIssueDate($value): void
    {
        $this->updatedPaymentTerms($this->payment_terms);
    }

    protected function rules(): array
    {
        return [
            'customer_name' => 'required|string|min:3',
            'billing_address' => 'required|string|min:3',
            'issue_date' => 'required|date',
            'due_date' => 'required|date',
            'items' => 'required|array|min:1',
            'items.*.item_description' => 'required|string',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.unit_price' => 'required|numeric|min:0',
        ];
    }



    public function addItem(): void
    {
        $this->items[] = [
            'tyre_product_id' => null,
            'item_description' => '',
            'quantity' => 1,
            'unit_label' => 'tyres',
            'unit_price' => 0,
            'amount' => 0,
        ];
        $this->recalculateTotals();
    }

    public bool $showDeleteItemModal = false;

    public ?int $deletingItemIndex = null;

    public ?string $deletingItemName = null;

    public function confirmRemoveItem(int $index): void
    {
        if (isset($this->items[$index])) {
            $this->deletingItemIndex = $index;
            $this->deletingItemName = ! empty($this->items[$index]['item_description'])
                ? $this->items[$index]['item_description']
                : 'Line Item #'.($index + 1);
            $this->showDeleteItemModal = true;
        }
    }

    public function cancelRemoveItem(): void
    {
        $this->showDeleteItemModal = false;
        $this->deletingItemIndex = null;
        $this->deletingItemName = null;
    }

    public function removeItem(?int $index = null): void
    {
        $targetIndex = $index ?? $this->deletingItemIndex;
        if ($targetIndex !== null && isset($this->items[$targetIndex])) {
            unset($this->items[$targetIndex]);
            $this->items = array_values($this->items);
            $this->recalculateTotals();
        }

        $this->showDeleteItemModal = false;
        $this->deletingItemIndex = null;
        $this->deletingItemName = null;
    }

    public function selectProduct(int $index, int $productId): void
    {
        $product = TyreProduct::find($productId);
        if ($product && isset($this->items[$index])) {
            $this->items[$index]['tyre_product_id'] = $product->id;
            $this->items[$index]['item_description'] = "{$product->brand} {$product->size} {$product->pattern}";
            $this->items[$index]['unit_price'] = (float) $product->unit_price_tzs;
            $this->items[$index]['amount'] = max(1, (int) $this->items[$index]['quantity']) * (float) $product->unit_price_tzs;
            $this->recalculateTotals();
        }
    }

    public function updated($property = null): void
    {
        $this->recalculateTotals();

        if ($property && in_array(explode('.', $property)[0], ['customer_name', 'billing_address', 'issue_date', 'due_date', 'items', 'new_pm_name', 'new_pm_account'])) {
            $this->validateOnly($property);
        }
    }

    public function updatedItems(): void
    {
        $this->recalculateTotals();
    }

    public function updatedDiscountTzs(): void
    {
        $this->recalculateTotals();
    }

    public function updatedTaxRate(): void
    {
        $this->recalculateTotals();
    }

    public function updatedTaxType(): void
    {
        $this->recalculateTotals();
    }

    public function setTaxType(string $type): void
    {
        $this->tax_type = $type;
        if ($type === 'exclusive') {
            $this->tax_rate = 0.0;
        } else {
            if ((float) $this->tax_rate == 0.0) {
                $this->tax_rate = 18.0;
            }
        }
        $this->recalculateTotals();
    }

    public function recalculateTotals(): void
    {
        $subtotal = 0.0;
        if (is_array($this->items)) {
            foreach ($this->items as $idx => $item) {
                $qty = is_numeric($item['quantity'] ?? null) ? max(0, (float) $item['quantity']) : 0.0;
                $price = is_numeric($item['unit_price'] ?? null) ? max(0, (float) $item['unit_price']) : 0.0;
                $lineAmount = round($qty * $price, 2);
                $this->items[$idx]['amount'] = $lineAmount;
                $subtotal += $lineAmount;
            }
        }

        $this->subtotal_tzs = (float) $subtotal;
        $discount = is_numeric($this->discount_tzs) ? max(0, (float) $this->discount_tzs) : 0.0;
        $discounted = max(0, $this->subtotal_tzs - $discount);

        if ($this->tax_type === 'exclusive') {
            // TAX Exclusive: 0% TAX applied by default (cannot be changed)
            $this->tax_rate = 0.0;
            $this->tax_amount_tzs = 0.0;
            $this->total_amount_tzs = (float) $discounted;
        } else {
            // TAX Inclusive: user can specify TAX e.g. 18%
            $rate = is_numeric($this->tax_rate) ? max(0, (float) $this->tax_rate) : 0.0;
            $this->tax_amount_tzs = $rate > 0 ? (float) round($discounted * ($rate / 100), 2) : 0.0;
            $this->total_amount_tzs = (float) round($discounted + $this->tax_amount_tzs, 2);
        }
    }

    public function addPaymentMethodQuickly(): void
    {
        if (auth()->user()?->isStaff()) {
            session()->flash('error', 'Unauthorized: Staff members cannot add payment methods. Please contact the administrator.');
            $this->showAddPaymentModal = false;
            return;
        }

        $this->validate([
            'new_pm_name' => 'required|string|min:3',
            'new_pm_account' => 'required|string',
        ]);

        $pm = PaymentMethod::create([
            'name' => $this->new_pm_name,
            'type' => $this->new_pm_type,
            'bank_name' => $this->new_pm_name,
            'account_number_or_till' => $this->new_pm_account,
            'account_name' => $this->new_pm_account_name,
            'branch' => $this->new_pm_branch,
            'is_active' => true,
        ]);

        $this->selected_payment_method_ids[] = $pm->id;
        $this->reset(['new_pm_name', 'new_pm_account', 'showAddPaymentModal']);
        session()->flash('pm_success', 'New payment channel added to invoice successfully.');
    }

    private function createInvoiceRecord(string $targetStatus = 'issued'): Invoice
    {
        $this->validate([
            'customer_name' => 'required|string|min:3',
            'billing_address' => 'required|string',
            'issue_date' => 'required|date',
            'due_date' => 'required|date',
            'items' => 'required|array|min:1',
            'items.*.item_description' => 'required|string',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.unit_price' => 'required|numeric|min:0',
        ]);

        $customer = null;
        if ($this->customer_id) {
            $customer = Customer::find($this->customer_id);
        }

        if (! $customer) {
            $customer = Customer::firstOrCreate(
                ['name' => $this->customer_name],
                [
                    'billing_address' => $this->billing_address,
                    'phone' => '+255 700 000 000',
                    'customer_type' => 'retail',
                ]
            );
        }

        $invoice = Invoice::create([
            'invoice_number' => $this->invoice_number,
            'customer_id' => $customer->id,
            'customer_name' => $this->customer_name,
            'billing_address' => $this->billing_address,
            'issuer_name' => $this->issuer_name,
            'issuer_phone' => $this->issuer_phone,
            'issue_date' => $this->issue_date,
            'due_date' => $this->due_date,
            'payment_terms' => $this->payment_terms,
            'status' => $targetStatus,
            'subtotal_tzs' => (float) $this->subtotal_tzs,
            'discount_tzs' => is_numeric($this->discount_tzs) ? (float) $this->discount_tzs : 0.0,
            'tax_rate_percent' => $this->tax_type === 'exclusive' ? 0.0 : (is_numeric($this->tax_rate) ? (float) $this->tax_rate : 0.0),
            'tax_type' => $this->tax_type,
            'tax_amount_tzs' => (float) $this->tax_amount_tzs,
            'total_amount_tzs' => (float) $this->total_amount_tzs,
            'amount_paid_tzs' => $targetStatus === 'paid' ? (float) $this->total_amount_tzs : 0.0,
            'selected_payment_method_ids' => array_values(array_map('intval', (array) $this->selected_payment_method_ids)),
            'notes' => $this->notes,
        ]);

        foreach ($this->items as $item) {
            $invoice->items()->create([
                'tyre_product_id' => $item['tyre_product_id'] ?? null,
                'item_description' => $item['item_description'],
                'quantity' => $item['quantity'],
                'unit_label' => $item['unit_label'] ?? 'tyres',
                'unit_price_tzs' => $item['unit_price'],
                'total_price_tzs' => $item['amount'],
            ]);
        }

        $this->saved_invoice_id = $invoice->id;

        return $invoice;
    }

    public function resetInvoiceForm(): void
    {
        $this->invoice_number = Invoice::generateKariakooInvoiceNumber();
        $this->issue_date = now()->format('Y-m-d');
        $this->due_date = now()->addDays(14)->format('Y-m-d');
        $this->saved_invoice_id = null;
        $this->discount_tzs = 0;

        $prod1 = TyreProduct::where('is_active', true)->first();
        $this->items = [];
        if ($prod1) {
            $this->items[] = [
                'tyre_product_id' => $prod1->id,
                'item_description' => "{$prod1->brand} {$prod1->size} {$prod1->pattern}",
                'quantity' => 1,
                'unit_label' => 'tyres',
                'unit_price' => (float) $prod1->unit_price_tzs,
                'amount' => (float) $prod1->unit_price_tzs,
            ];
        } else {
            $this->items[] = [
                'tyre_product_id' => null,
                'item_description' => 'Commercial Heavy Truck Radial Tyre',
                'quantity' => 1,
                'unit_label' => 'tyres',
                'unit_price' => 500000,
                'amount' => 500000,
            ];
        }

        $this->recalculateTotals();
    }

    public function downloadPdf()
    {
        $invoice = $this->createInvoiceRecord('issued');

        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('invoices.pdf', [
            'invoice' => $invoice->load(['customer', 'items.tyreProduct']),
            'paymentMethods' => PaymentMethod::whereIn('id', $invoice->selected_payment_method_ids ?? [])->get(),
        ]);

        $pdfContent = $pdf->output();
        $filename = "Anagkazo_Invoice_{$invoice->invoice_number}.pdf";
        $savedNumber = $invoice->invoice_number;

        // Reset form and remain in the invoice section for the next invoice
        $this->resetInvoiceForm();

        session()->flash('success', "Invoice #{$savedNumber} saved & PDF downloaded. Returned to invoice section ready for your next invoice.");

        return response()->streamDownload(
            fn () => print($pdfContent),
            $filename,
            [
                'Content-Type' => 'application/pdf',
                'Content-Disposition' => "attachment; filename=\"{$filename}\"",
            ]
        );
    }

    public function saveInvoice(string $targetStatus = 'issued')
    {
        $invoice = $this->createInvoiceRecord($targetStatus);

        return redirect()->route('invoices.show', $invoice);
    }

    public function render()
    {
        $this->recalculateTotals();

        $activeMethods = PaymentMethod::where('is_active', true)->orderByDesc('created_at')->orderByDesc('id')->get();

        return view('livewire.invoices.create-invoice', [
            'customers' => Customer::orderByDesc('created_at')->orderByDesc('id')->get(),
            'products' => TyreProduct::where('is_active', true)->orderByDesc('created_at')->orderByDesc('id')->get(),
            'available_payment_methods' => $activeMethods,
            'selected_methods' => PaymentMethod::whereIn('id', $this->selected_payment_method_ids)->get(),
        ]);
    }
}
