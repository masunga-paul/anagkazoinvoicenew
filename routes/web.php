<?php

use App\Livewire\Customers\CustomerManager;
use App\Livewire\Dashboard\Overview;
use App\Livewire\Invoices\CreateInvoice;
use App\Livewire\Invoices\InvoiceList;
use App\Livewire\Products\ProductManager;
use App\Livewire\Reports\ReportsIndex;
use App\Livewire\Settings\PaymentMethodsManager;
use App\Livewire\Settings\SecurityCredentials;
use App\Models\Invoice;
use Illuminate\Support\Facades\Route;

// Default entry redirects to Dashboard (which redirects to Login for guests)
Route::get('/', function () {
    return redirect()->route('dashboard');
})->name('home');

// Protected ERP & Dashboard Routes
Route::middleware(['auth'])->group(function () {
    // Dashboard Overview
    Route::get('dashboard', Overview::class)->name('dashboard');

    // Customers Management
    Route::get('customers', CustomerManager::class)->name('customers.index');

    // Products & Inventory Management
    Route::get('products', ProductManager::class)->name('products.index');

    // Reports & Financial Analytics
    Route::get('reports', ReportsIndex::class)->name('reports.index');

    // Payment Methods Settings
    Route::get('payment-methods', PaymentMethodsManager::class)->name('payment-methods.index');

    // Security Credentials (Admin Only)
    Route::get('security-credentials', SecurityCredentials::class)->name('security-credentials.index');

    // Invoices Management
    Route::prefix('invoices')->name('invoices.')->group(function () {
        Route::get('/', InvoiceList::class)->name('index');
        Route::get('/create', CreateInvoice::class)->name('create');

        Route::get('/{invoice}', function (Invoice $invoice) {
            $invoice->load(['customer', 'items']);

            return view('invoices.show', compact('invoice'));
        })->name('show');

        Route::get('/{invoice}/print', function (Invoice $invoice) {
            $invoice->load(['customer', 'items']);

            return view('invoices.print', compact('invoice'));
        })->name('print');

        Route::get('/{invoice}/download', function (Invoice $invoice) {
            $invoice->load(['customer', 'items']);

            $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('invoices.pdf', compact('invoice'));

            return $pdf->download("Anagkazo_Invoice_{$invoice->invoice_number}.pdf");
        })->name('download');
    });
});

// Direct public download link for customers receiving WhatsApp invoice dispatch
Route::get('public-invoice/{invoice}/download', function (Invoice $invoice) {
    $invoice->load(['customer', 'items']);

    $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('invoices.pdf', compact('invoice'));

    return $pdf->download("Anagkazo_Invoice_{$invoice->invoice_number}.pdf");
})->name('invoices.public-download');

require __DIR__.'/settings.php';
