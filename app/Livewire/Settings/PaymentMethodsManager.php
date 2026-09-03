<?php

namespace App\Livewire\Settings;

use App\Models\PaymentMethod;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('layouts.empty')]
#[Title('Payment Methods - Anagkazo Tyres Kariakoo')]
class PaymentMethodsManager extends Component
{
    public string $name = '';

    public string $type = 'bank_transfer';

    public string $bank_name = '';

    public string $account_number_or_till = '';

    public string $account_name = 'Anagkazo Tyres Ltd';

    public string $branch = 'Kariakoo, Dar es Salaam';

    public string $logo_url = '';

    public string $instructions = '';

    public bool $is_default = false;

    public bool $showForm = false;

    public ?int $editingId = null;

    public function mount(): void
    {
        if (auth()->check() && auth()->user()->isStaff()) {
            session()->flash('error', 'Unauthorized access: Payment Channel Settings are restricted to Administrators.');
            $this->redirect(route('invoices.create'), navigate: true);
            return;
        }
    }

    protected $rules = [
        'name' => 'required|string|min:3',
        'type' => 'required|in:bank_transfer,mobile_money,cash,cheque',
        'account_number_or_till' => 'required|string',
        'account_name' => 'required|string',
        'logo_url' => 'nullable|url',
    ];

    public function updated($propertyName): void
    {
        $this->validateOnly($propertyName);
    }

    public function createPaymentMethod(): void
    {
        $this->validate();

        PaymentMethod::create([
            'name' => $this->name,
            'type' => $this->type,
            'bank_name' => $this->bank_name ?: $this->name,
            'account_number_or_till' => $this->account_number_or_till,
            'account_name' => $this->account_name,
            'branch' => $this->branch,
            'logo_url' => $this->logo_url ?: null,
            'instructions' => $this->instructions,
            'is_default' => $this->is_default,
            'is_active' => true,
        ]);

        $this->resetForm();
        session()->flash('success', 'Payment channel added successfully.');
    }

    public function toggleStatus(int $id): void
    {
        $pm = PaymentMethod::find($id);
        if ($pm) {
            $pm->update(['is_active' => ! $pm->is_active]);
            session()->flash('success', 'Payment channel status updated successfully.');
        }
    }

    public bool $showDeleteModal = false;

    public ?int $deletingMethodId = null;

    public ?string $deletingMethodName = null;

    public function editMethod(int $id): void
    {
        $pm = PaymentMethod::findOrFail($id);
        $this->editingId = $pm->id;
        $this->name = $pm->name;
        $this->type = $pm->type;
        $this->bank_name = $pm->bank_name;
        $this->account_number_or_till = $pm->account_number_or_till;
        $this->account_name = $pm->account_name;
        $this->branch = $pm->branch;
        $this->logo_url = $pm->logo_url ?? '';
        $this->instructions = $pm->instructions;
        $this->is_default = (bool) $pm->is_default;
        $this->showForm = true;
    }

    public function saveMethod(): void
    {
        if ($this->editingId) {
            $this->validate();
            $pm = PaymentMethod::findOrFail($this->editingId);
            $pm->update([
                'name' => $this->name,
                'type' => $this->type,
                'bank_name' => $this->bank_name ?: $this->name,
                'account_number_or_till' => $this->account_number_or_till,
                'account_name' => $this->account_name,
                'branch' => $this->branch,
                'logo_url' => $this->logo_url ?: null,
                'instructions' => $this->instructions,
                'is_default' => $this->is_default,
            ]);
            $this->resetForm();
            session()->flash('success', 'Payment channel updated successfully.');
        } else {
            $this->createPaymentMethod();
        }
    }

    public function confirmDelete(int $id): void
    {
        $pm = PaymentMethod::findOrFail($id);
        $this->deletingMethodId = $pm->id;
        $this->deletingMethodName = "{$pm->name} ({$pm->account_number_or_till})";
        $this->showDeleteModal = true;
    }

    public function cancelDelete(): void
    {
        $this->showDeleteModal = false;
        $this->deletingMethodId = null;
        $this->deletingMethodName = null;
    }

    public function deleteMethod(?int $id = null): void
    {
        $targetId = $id ?? $this->deletingMethodId;
        if (! $targetId) {
            return;
        }

        $pm = PaymentMethod::find($targetId);
        if ($pm) {
            $pm->delete();
            session()->flash('success', 'Payment channel deleted successfully.');
        }

        $this->showDeleteModal = false;
        $this->deletingMethodId = null;
        $this->deletingMethodName = null;
    }

    public function resetForm(): void
    {
        $this->reset(['name', 'type', 'bank_name', 'account_number_or_till', 'logo_url', 'instructions', 'is_default', 'showForm', 'editingId']);
        $this->account_name = 'Anagkazo Tyres Ltd';
        $this->branch = 'Kariakoo, Dar es Salaam';
    }

    public function render()
    {
        return view('livewire.settings.payment-methods-manager', [
            'methods' => PaymentMethod::latest()->get(),
        ]);
    }
}
