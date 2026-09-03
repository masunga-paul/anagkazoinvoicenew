<?php

namespace App\Livewire\Customers;

use App\Models\Customer;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.empty')]
#[Title('Customer Directory & Accounts - Anagkazo Tyres Kariakoo')]
class CustomerManager extends Component
{
    use WithPagination;

    #[Url]
    public string $search = '';

    #[Url]
    public string $typeFilter = 'all'; // 'all', 'retail', 'corporate_ngo', 'government'

    #[Url]
    public string $tierFilter = 'all'; // 'all', 'premium', 'medium', 'standard'

    public string $invoiceStatusFilter = 'all'; // 'all', 'has_overdue', 'has_pending', 'all_paid'

    public string $sortBy = 'latest'; // 'latest', 'name_asc', 'name_desc', 'invoices_desc', 'balance_desc', 'spent_desc'

    public function updatingTierFilter(): void
    {
        $this->resetPage();
    }

    public function updatingTypeFilter(): void
    {
        $this->resetPage();
    }

    // Create / Edit Modal State
    public bool $showCustomerModal = false;

    public bool $isEditing = false;

    public ?int $editingCustomerId = null;

    // Form inputs
    public string $name = '';

    public string $contact_person = '';

    public string $phone = '';

    public string $email = '';

    public string $tin_number = '';

    public string $vrn_number = '';

    public string $billing_address = '';

    public string $customer_type = 'retail';

    public string $notes = '';

    // Delete Confirmation Modal State
    public bool $showDeleteModal = false;

    public ?int $deletingCustomerId = null;

    public ?string $deletingCustomerName = null;

    protected function rules(): array
    {
        return [
            'name' => 'required|string|min:2|max:255',
            'contact_person' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:50',
            'email' => ['nullable', 'string', 'email:rfc,filter', 'max:255', 'regex:/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/'],
            'tin_number' => 'nullable|string|max:50',
            'vrn_number' => 'nullable|string|max:50',
            'billing_address' => 'required|string|min:3',
            'customer_type' => 'required|in:retail,corporate_ngo,government',
            'notes' => 'nullable|string|max:1000',
        ];
    }

    protected function messages(): array
    {
        return [
            'name.required' => 'Customer or company name is required.',
            'name.min' => 'Customer name must be at least 2 characters.',
            'billing_address.required' => 'Physical & billing address is required.',
            'email.email' => 'Please enter a valid email address (e.g. info@company.co.tz).',
            'email.regex' => 'The email format must be valid with a domain and extension (e.g. info@company.co.tz).',
        ];
    }

    public function updated($propertyName): void
    {
        $this->validateOnly($propertyName);
    }

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function openCreateModal(): void
    {
        if (auth()->user()?->isStaff()) {
            session()->flash('error', 'Staff role has view-only access to customers. Contact Administrator to register new clients.');
            return;
        }

        $this->resetValidation();
        $this->reset([
            'name',
            'contact_person',
            'phone',
            'email',
            'tin_number',
            'vrn_number',
            'billing_address',
            'customer_type',
            'notes',
            'isEditing',
            'editingCustomerId',
        ]);

        $this->customer_type = 'retail';
        $this->showCustomerModal = true;
    }

    public function editCustomer(int $id): void
    {
        if (auth()->user()?->isStaff()) {
            session()->flash('error', 'Staff role has view-only access to customer profiles.');
            return;
        }

        $this->resetValidation();
        $customer = Customer::findOrFail($id);

        $this->editingCustomerId = $customer->id;
        $this->name = $customer->name;
        $this->contact_person = $customer->contact_person ?? '';
        $this->phone = $customer->phone ?? '';
        $this->email = $customer->email ?? '';
        $this->tin_number = $customer->tin_number ?? '';
        $this->vrn_number = $customer->vrn_number ?? '';
        $this->billing_address = $customer->billing_address ?? '';
        $this->customer_type = $customer->customer_type ?? 'retail';
        $this->notes = $customer->notes ?? '';

        $this->isEditing = true;
        $this->showCustomerModal = true;
    }

    public function saveCustomer(): void
    {
        if (auth()->user()?->isStaff()) {
            abort(403, 'Unauthorized action: Staff cannot modify customer profiles.');
        }

        $validated = $this->validate();

        if ($this->isEditing && $this->editingCustomerId) {
            $customer = Customer::findOrFail($this->editingCustomerId);
            $customer->update($validated);
            session()->flash('message', "Customer '{$customer->name}' updated successfully.");
        } else {
            $customer = Customer::create($validated);
            session()->flash('message', "Customer '{$customer->name}' registered successfully.");
            $this->sortBy = 'latest';
        }

        $this->showCustomerModal = false;
        $this->reset([
            'name',
            'contact_person',
            'phone',
            'email',
            'tin_number',
            'vrn_number',
            'billing_address',
            'customer_type',
            'notes',
            'isEditing',
            'editingCustomerId',
        ]);
    }

    public function confirmDelete(int $id): void
    {
        if (auth()->user()?->isStaff()) {
            session()->flash('error', 'Staff cannot delete customer accounts.');
            return;
        }

        $customer = Customer::findOrFail($id);
        $this->deletingCustomerId = $customer->id;
        $this->deletingCustomerName = $customer->name;
        $this->showDeleteModal = true;
    }

    public function deleteCustomer(): void
    {
        if (auth()->user()?->isStaff()) {
            abort(403, 'Unauthorized action: Staff cannot delete customers.');
        }

        if ($this->deletingCustomerId) {
            $customer = Customer::findOrFail($this->deletingCustomerId);
            $name = $customer->name;
            $customer->delete();

            session()->flash('message', "Customer '{$name}' deleted successfully.");
        }

        $this->showDeleteModal = false;
        $this->deletingCustomerId = null;
        $this->deletingCustomerName = null;
    }

    public function closeModal(): void
    {
        $this->showCustomerModal = false;
        $this->resetValidation();
    }

    public function closeDeleteModal(): void
    {
        $this->showDeleteModal = false;
        $this->deletingCustomerId = null;
        $this->deletingCustomerName = null;
    }

    public function render()
    {
        $query = Customer::with('invoices');

        if (! empty($this->search)) {
            $search = trim($this->search);
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('contact_person', 'like', "%{$search}%")
                    ->orWhere('phone', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('tin_number', 'like', "%{$search}%")
                    ->orWhere('billing_address', 'like', "%{$search}%");
            });
        }

        if ($this->typeFilter !== 'all') {
            $query->where('customer_type', $this->typeFilter);
        }

        // Stats across all customers
        $allCustomers = Customer::with('invoices')->get();
        $totalCustomersCount = $allCustomers->count();
        $retailCount = $allCustomers->where('customer_type', 'retail')->count();
        $corporateNgoCount = $allCustomers->where('customer_type', 'corporate_ngo')->count();
        $governmentCount = $allCustomers->where('customer_type', 'government')->count();

        $premiumCount = 0;
        $mediumCount = 0;
        $standardCount = 0;

        $totalReceivables = 0;
        $totalBilledAll = 0;
        $customersWithOverdueCount = 0;
        $customersWithPendingCount = 0;

        foreach ($allCustomers as $c) {
            $spent = (float) $c->invoices->sum('total_amount_tzs');
            $paid = (float) $c->invoices->sum('amount_paid_tzs');
            $totalBilledAll += $spent;
            $totalReceivables += max(0, $spent - $paid);
            if ($c->overdue_invoices_count > 0) {
                $customersWithOverdueCount++;
            }
            if ($c->pending_invoices_count > 0) {
                $customersWithPendingCount++;
            }

            if ($spent >= 100000000) {
                $premiumCount++;
            } elseif ($spent >= 50000000) {
                $mediumCount++;
            } else {
                $standardCount++;
            }
        }

        // Mapping and calculation
        $customersList = $query->get()->map(function ($customer) {
            $totalSpent = (float) $customer->invoices->sum('total_amount_tzs');
            $totalPaid = (float) $customer->invoices->sum('amount_paid_tzs');
            $balance = max(0, $totalSpent - $totalPaid);

            $paidCount = $customer->paid_invoices_count;
            $pendingCount = $customer->pending_invoices_count;
            $overdueCount = $customer->overdue_invoices_count;

            $customer->computed_spent = $totalSpent;
            $customer->computed_paid = $totalPaid;
            $customer->computed_balance = $balance;
            $customer->computed_invoices_count = $customer->invoices->count();
            $customer->computed_paid_count = $paidCount;
            $customer->computed_pending_count = $pendingCount;
            $customer->computed_overdue_count = $overdueCount;

            return $customer;
        });

        // Filter by tier if requested
        if ($this->tierFilter === 'premium') {
            $customersList = $customersList->filter(fn ($c) => $c->computed_spent >= 100000000);
        } elseif ($this->tierFilter === 'medium') {
            $customersList = $customersList->filter(fn ($c) => $c->computed_spent >= 50000000 && $c->computed_spent < 100000000);
        } elseif ($this->tierFilter === 'standard') {
            $customersList = $customersList->filter(fn ($c) => $c->computed_spent < 50000000);
        }

        // Filter by invoice status if requested
        if ($this->invoiceStatusFilter === 'has_overdue') {
            $customersList = $customersList->filter(fn ($c) => $c->computed_overdue_count > 0);
        } elseif ($this->invoiceStatusFilter === 'has_pending') {
            $customersList = $customersList->filter(fn ($c) => $c->computed_pending_count > 0);
        } elseif ($this->invoiceStatusFilter === 'all_paid') {
            $customersList = $customersList->filter(fn ($c) => $c->computed_balance <= 0 && $c->computed_invoices_count > 0);
        }

        if ($this->sortBy === 'name_asc') {
            $customersList = $customersList->sortBy('name', SORT_NATURAL | SORT_FLAG_CASE);
        } elseif ($this->sortBy === 'name_desc') {
            $customersList = $customersList->sortByDesc('name', SORT_NATURAL | SORT_FLAG_CASE);
        } elseif ($this->sortBy === 'invoices_desc') {
            $customersList = $customersList->sortByDesc('computed_invoices_count');
        } elseif ($this->sortBy === 'balance_desc') {
            $customersList = $customersList->sortByDesc('computed_balance');
        } elseif ($this->sortBy === 'spent_desc') {
            $customersList = $customersList->sortByDesc('computed_spent');
        } else {
            $customersList = $customersList->sortByDesc(fn ($c) => $c->created_at?->timestamp ?? $c->id);
        }

        return view('livewire.customers.customer-manager', [
            'customers' => $customersList,
            'totalCustomersCount' => $totalCustomersCount,
            'retailCount' => $retailCount,
            'corporateNgoCount' => $corporateNgoCount,
            'governmentCount' => $governmentCount,
            'premiumCount' => $premiumCount,
            'mediumCount' => $mediumCount,
            'standardCount' => $standardCount,
            'totalReceivables' => $totalReceivables,
            'totalBilledAll' => $totalBilledAll,
        ]);
    }
}
