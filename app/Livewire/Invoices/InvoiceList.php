<?php

namespace App\Livewire\Invoices;

use App\Models\Invoice;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.empty')]
#[Title('All Invoices - Anagkazo Tyres Kariakoo')]
class InvoiceList extends Component
{
    use WithPagination;

    public string $search = '';

    public string $statusFilter = 'all';

    public function mount(): void
    {
        if (auth()->user()?->isStaff()) {
            abort(403, 'Unauthorized access: Invoice Records are restricted to Administrators.');
        }
    }

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function updatingStatusFilter(): void
    {
        $this->resetPage();
    }

    public function markAsPaid(int $invoiceId): void
    {
        $invoice = Invoice::find($invoiceId);
        if ($invoice) {
            $invoice->update([
                'status' => 'paid',
                'amount_paid_tzs' => $invoice->total_amount_tzs,
            ]);
            session()->flash('success', "Invoice {$invoice->invoice_number} marked as Paid.");
        }
    }

    public bool $showDeleteModal = false;

    public ?int $deletingInvoiceId = null;

    public ?string $deletingInvoiceNumber = null;

    public function confirmDelete(int $id): void
    {
        if (auth()->user()?->isStaff()) {
            session()->flash('error', 'Staff cannot delete invoice records.');
            return;
        }

        $invoice = Invoice::findOrFail($id);
        $this->deletingInvoiceId = $invoice->id;
        $this->deletingInvoiceNumber = $invoice->invoice_number;
        $this->showDeleteModal = true;
    }

    public function cancelDelete(): void
    {
        $this->showDeleteModal = false;
        $this->deletingInvoiceId = null;
        $this->deletingInvoiceNumber = null;
    }

    public function deleteInvoice(?int $invoiceId = null): void
    {
        if (auth()->user()?->isStaff()) {
            abort(403, 'Unauthorized action: Staff cannot delete invoices.');
        }

        $targetId = $invoiceId ?? $this->deletingInvoiceId;
        if (! $targetId) {
            return;
        }

        $invoice = Invoice::find($targetId);
        if ($invoice) {
            $num = $invoice->invoice_number;
            $invoice->delete();
            session()->flash('success', "Invoice {$num} deleted successfully.");
        }

        $this->showDeleteModal = false;
        $this->deletingInvoiceId = null;
        $this->deletingInvoiceNumber = null;
    }

    public function render()
    {
        $query = Invoice::with(['customer', 'items'])->orderByDesc('created_at')->orderByDesc('id');

        if ($this->search) {
            $query->where(function ($q) {
                $q->where('invoice_number', 'like', "%{$this->search}%")
                    ->orWhere('customer_name', 'like', "%{$this->search}%");
            });
        }

        if ($this->statusFilter === 'paid') {
            $query->paid();
        } elseif ($this->statusFilter === 'pending') {
            $query->pending();
        } elseif ($this->statusFilter === 'overdue') {
            $query->overdue();
        } elseif ($this->statusFilter !== 'all') {
            $query->where('status', $this->statusFilter);
        }

        $totalIssued = (float) Invoice::sum('total_amount_tzs');
        $totalPaid = (float) Invoice::sum('amount_paid_tzs');
        
        $pendingQuery = Invoice::pending();
        $totalPending = (float) $pendingQuery->get()->sum('balance_tzs');
        $pendingCount = $pendingQuery->count();

        $overdueQuery = Invoice::overdue();
        $totalOverdue = (float) $overdueQuery->get()->sum('balance_tzs');
        $overdueCount = $overdueQuery->count();

        $paidCount = Invoice::paid()->count();
        $totalCount = Invoice::count();

        return view('livewire.invoices.invoice-list', [
            'invoices' => $query->paginate(10),
            'totalIssued' => $totalIssued,
            'totalPaid' => $totalPaid,
            'totalPending' => $totalPending,
            'totalOverdue' => $totalOverdue,
            'totalCount' => $totalCount,
            'paidCount' => $paidCount,
            'pendingCount' => $pendingCount,
            'overdueCount' => $overdueCount,
        ]);
    }
}
