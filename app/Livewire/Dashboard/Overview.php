<?php

namespace App\Livewire\Dashboard;

use App\Models\Customer;
use App\Models\Invoice;
use App\Models\InvoiceItem;
use App\Models\TyreProduct;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('layouts.empty')]
#[Title('Tyre ERP Dashboard - Kariakoo, Dar es Salaam')]
class Overview extends Component
{
    public bool $showResetDataModal = false;

    public string $confirmResetText = '';

    public function mount()
    {
        if (auth()->check() && auth()->user()->isStaff()) {
            return $this->redirect(route('invoices.create'), navigate: true);
        }
    }

    public function openResetDataModal(): void
    {
        if (auth()->user()?->isStaff()) {
            abort(403, 'Unauthorized action: Staff cannot reset database records.');
        }

        $this->confirmResetText = '';
        $this->showResetDataModal = true;
    }

    public function cancelResetDataModal(): void
    {
        $this->confirmResetText = '';
        $this->showResetDataModal = false;
    }

    public function wipeOperationalData(): void
    {
        if (! auth()->check() || ! auth()->user()->isAdmin()) {
            abort(403, 'Unauthorized action: Only Administrators can reset database data.');
        }

        DB::transaction(function () {
            InvoiceItem::query()->delete();
            Invoice::query()->delete();
            TyreProduct::query()->delete();
            Customer::query()->delete();
        });

        $this->showResetDataModal = false;
        $this->confirmResetText = '';

        session()->flash('success', 'Database cleared successfully! All invoices, stock inventory, and customer records have been deleted. Login credentials and payment methods were preserved.');
    }

    public function render()
    {
        $totalInvoicesCount = Invoice::count();
        $totalIssuedAmount = (float) Invoice::sum('total_amount_tzs');
        $totalPaidAmount = (float) Invoice::sum('amount_paid_tzs');
        $paidCount = Invoice::paid()->count();

        $pendingInvoices = Invoice::pending()->get();
        $totalPendingAmount = (float) $pendingInvoices->sum('balance_tzs');
        $pendingCount = $pendingInvoices->count();

        $overdueInvoices = Invoice::overdue()->get();
        $totalOverdueAmount = (float) $overdueInvoices->sum('balance_tzs');
        $overdueCount = $overdueInvoices->count();

        // Low stock alerts
        $lowStockProducts = TyreProduct::whereColumn('stock_quantity', '<=', 'reorder_threshold')
            ->where('is_active', true)
            ->get();

        // All stock products
        $allProducts = TyreProduct::where('is_active', true)->orderBy('stock_quantity', 'asc')->get();

        // Category breakdown dynamically computed from database
        $distinctCategories = TyreProduct::where('is_active', true)
            ->whereNotNull('category')
            ->where('category', '!=', '')
            ->pluck('category')
            ->unique();

        $categoryBreakdown = [];
        foreach ($distinctCategories as $cat) {
            $catLower = strtolower($cat);
            $icon = 'disc';
            $bgColor = 'from-slate-50 to-zinc-100';
            $border = 'border-zinc-200';

            if (str_contains($catLower, 'truck') || str_contains($catLower, 'tbr') || str_contains($catLower, 'bus')) {
                $icon = 'truck';
                $bgColor = 'from-amber-500/10 to-orange-500/10';
                $border = 'border-amber-200';
            } elseif (str_contains($catLower, 'suv') || str_contains($catLower, '4x4') || str_contains($catLower, 'terrain')) {
                $icon = 'disc';
                $bgColor = 'from-zinc-100 to-zinc-200/50';
                $border = 'border-zinc-200';
            } elseif (str_contains($catLower, 'car') || str_contains($catLower, 'passenger') || str_contains($catLower, 'pcr')) {
                $icon = 'car';
                $bgColor = 'from-slate-50 to-zinc-100';
                $border = 'border-zinc-200';
            }

            $sizes = TyreProduct::where('is_active', true)->where('category', $cat)->pluck('size')->filter()->take(2)->implode(', ');

            $categoryBreakdown[$cat] = [
                'name' => $cat,
                'stock' => (int) TyreProduct::where('is_active', true)->where('category', $cat)->sum('stock_quantity'),
                'models' => (int) TyreProduct::where('is_active', true)->where('category', $cat)->count(),
                'sample_size' => $sizes ?: 'Custom Sizes',
                'bg_color' => $bgColor,
                'border' => $border,
                'icon' => $icon,
            ];
        }

        // Recent Invoices (Always show newest at top)
        $recentInvoices = Invoice::with('customer')->orderByDesc('created_at')->orderByDesc('id')->take(6)->get();

        return view('livewire.dashboard.overview', [
            'totalInvoicesCount' => $totalInvoicesCount,
            'totalIssuedAmount' => $totalIssuedAmount,
            'totalPaidAmount' => $totalPaidAmount,
            'paidCount' => $paidCount,
            'totalPendingAmount' => $totalPendingAmount,
            'pendingCount' => $pendingCount,
            'totalOverdueAmount' => $totalOverdueAmount,
            'overdueCount' => $overdueCount,
            'lowStockProducts' => $lowStockProducts,
            'allProducts' => $allProducts,
            'categoryBreakdown' => $categoryBreakdown,
            'recentInvoices' => $recentInvoices,
            'totalCustomers' => Customer::count(),
        ]);
    }
}
