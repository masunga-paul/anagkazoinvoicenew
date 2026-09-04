<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="light">
<head>
    @include('partials.head')
    <title>Invoice {{ $invoice->invoice_number }} - Anagkazo Tyres</title>
</head>
<body class="min-h-screen bg-[#f3f4f6] text-zinc-900 py-6 px-4 sm:px-6 lg:px-8 font-sans antialiased">
    {{-- Global Top Navigation Bar with Deep Navy Styling --}}
    <header class="sticky top-4 z-40 max-w-7xl mx-auto mb-8 bg-white/95 backdrop-blur-md border border-zinc-200/90 rounded-2xl px-5 py-2.5 shadow-md flex items-center justify-between gap-4 transition-all duration-200">
        {{-- Left: Brand & Main Navigation --}}
        <div class="flex items-center space-x-4 lg:space-x-6 shrink-0">
            <a href="{{ auth()->user()?->isAdmin() ? route('dashboard') : route('invoices.create') }}" class="flex items-center space-x-2.5 shrink-0 group">
                <img src="{{ asset('images/logo.png') }}" alt="Anagkazo Logo" class="h-8 w-auto object-contain shrink-0">
                <div class="whitespace-nowrap">
                    <span class="font-extrabold text-sm tracking-tight text-zinc-900 block leading-tight">ANAGKAZO AUTOPARTS</span>
                    <span class="text-[10px] text-zinc-400 font-semibold uppercase tracking-wider block">Kariakoo, DSM</span>
                </div>
            </a>

            <nav class="hidden md:flex items-center space-x-1 shrink-0">
                @if(auth()->user()?->isAdmin())
                    <a href="{{ route('dashboard') }}" wire:navigate class="whitespace-nowrap px-3 py-1.5 text-xs font-semibold rounded-xl transition text-zinc-600 hover:text-zinc-900 hover:bg-zinc-100">Dashboard</a>
                @endif
                <a href="{{ route('invoices.create') }}" wire:navigate class="whitespace-nowrap px-3 py-1.5 text-xs font-semibold rounded-xl transition text-zinc-600 hover:text-zinc-900 hover:bg-zinc-100">Invoice</a>
                @if(auth()->user()?->isAdmin())
                    <a href="{{ route('invoices.index') }}" wire:navigate class="whitespace-nowrap px-3 py-1.5 text-xs font-semibold rounded-xl transition bg-[#0a192f] text-white shadow-xs">Records</a>
                @endif
                <a href="{{ route('products.index') }}" wire:navigate class="whitespace-nowrap px-3 py-1.5 text-xs font-semibold rounded-xl transition text-zinc-600 hover:text-zinc-900 hover:bg-zinc-100">Stocks</a>
                <a href="{{ route('customers.index') }}" wire:navigate class="whitespace-nowrap px-3 py-1.5 text-xs font-semibold rounded-xl transition text-zinc-600 hover:text-zinc-900 hover:bg-zinc-100">Customers</a>
                @if(auth()->user()?->isAdmin())
                    <a href="{{ route('reports.index') }}" wire:navigate class="whitespace-nowrap px-3 py-1.5 text-xs font-semibold rounded-xl transition text-zinc-600 hover:text-zinc-900 hover:bg-zinc-100">Reports</a>
                    <a href="{{ route('payment-methods.index') }}" wire:navigate class="whitespace-nowrap px-3 py-1.5 text-xs font-semibold rounded-xl transition text-zinc-600 hover:text-zinc-900 hover:bg-zinc-100">Payment Channels</a>
                @endif
            </nav>
        </div>

        {{-- Right: Role & Actions --}}
        <div class="flex items-center space-x-2.5 shrink-0">
            @if(auth()->user()?->isAdmin())
                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-bold bg-amber-100 text-amber-900 border border-amber-300 whitespace-nowrap">
                    Admin
                </span>
            @else
                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-bold bg-blue-100 text-blue-900 border border-blue-200 whitespace-nowrap">
                    Staff
                </span>
            @endif

            <a href="{{ route('invoices.create') }}" class="inline-flex items-center whitespace-nowrap px-3.5 py-1.5 text-xs font-bold text-white bg-[#1e3a8a] hover:bg-[#1d4ed8] rounded-xl shadow-xs transition cursor-pointer">
                <x-lucide name="file-plus-2" class="w-3.5 h-3.5 mr-1 text-blue-300" />
                New Invoice
            </a>

            <div class="flex items-center border-l border-zinc-200 pl-2.5">
                @auth
                    <form method="POST" action="{{ route('logout') }}" class="inline">
                        @csrf
                        <button type="submit" class="inline-flex items-center gap-1 whitespace-nowrap px-3 py-1.5 text-xs font-semibold text-zinc-700 hover:text-rose-600 bg-zinc-100 hover:bg-rose-50 border border-zinc-200 hover:border-rose-200 rounded-xl transition cursor-pointer" title="Log out">
                            <x-lucide name="log-out" class="w-3.5 h-3.5" />
                            <span>Logout</span>
                        </button>
                    </form>
                @endauth
            </div>
        </div>
    </header>

    <main class="max-w-4xl mx-auto space-y-6">
        {{-- Action Bar --}}
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div class="flex items-center space-x-3">
                <a href="{{ route('invoices.create') }}" class="inline-flex items-center px-4 py-2 text-xs font-bold text-white bg-[#1e3a8a] hover:bg-[#1d4ed8] rounded-xl shadow-xs transition">
                    <x-lucide name="plus-circle" class="w-3.5 h-3.5 mr-1.5 text-blue-300" />
                    Create New Invoice
                </a>
                @if(auth()->user()?->isAdmin())
                    <a href="{{ route('invoices.index') }}" class="inline-flex items-center text-xs font-semibold text-zinc-600 hover:text-zinc-900 transition">
                        <x-lucide name="arrow-left" class="w-3.5 h-3.5 mr-1" />
                        All Records
                    </a>
                @endif
            </div>

            <div class="flex items-center space-x-2">
                <a 
                    href="{{ route('invoices.download', $invoice) }}" 
                    onclick="setTimeout(function(){ window.location.href='{{ auth()->user()?->isAdmin() ? route('invoices.index') : route('invoices.create') }}'; }, 1000);"
                    class="inline-flex items-center px-3.5 py-1.5 text-xs font-bold text-white bg-[#0a192f] hover:bg-[#1e3a8a] rounded-xl shadow-xs transition cursor-pointer"
                >
                    <x-lucide name="download" class="w-3.5 h-3.5 mr-1.5 text-blue-400" />
                    Download PDF
                </a>

                <a href="{{ route('invoices.print', $invoice) }}" target="_blank" class="inline-flex items-center px-3.5 py-1.5 text-xs font-semibold text-zinc-700 bg-white border border-zinc-300 hover:bg-zinc-50 rounded-xl shadow-xs transition">
                    <x-lucide name="printer" class="w-3.5 h-3.5 mr-1.5 text-zinc-500" />
                    Print
                </a>

                @php
                    $pdfDownloadUrl = route('invoices.public-download', $invoice);
                    $waMsg = urlencode("Hello " . ($invoice->customer_name ?: 'Customer') . ",\n\nHere is your official tax invoice from Anagkazo Autoparts Ltd:\n• Invoice No: " . $invoice->invoice_number . "\n• Total Amount: TZS " . number_format($invoice->total_amount_tzs) . "\n• Status: " . ucfirst($invoice->status) . "\n\n📄 Download your official PDF Invoice here:\n" . $pdfDownloadUrl . "\n\nThank you for doing business with Anagkazo Tyres Kariakoo!");
                    $customerPhone = preg_replace('/[^0-9]/', '', $invoice->customer?->phone ?? '');
                @endphp
                <a href="https://wa.me/{{ $customerPhone }}?text={{ $waMsg }}" target="_blank" class="inline-flex items-center px-3.5 py-1.5 text-xs font-semibold text-emerald-800 bg-emerald-50 border border-emerald-200 hover:bg-emerald-100 rounded-xl shadow-xs transition">
                    <x-lucide name="message-circle" class="w-3.5 h-3.5 mr-1.5 text-emerald-600" />
                    WhatsApp
                </a>
            </div>
        </div>
                @php
                    $badgeClass = match($invoice->payment_status) {
                        'paid' => 'bg-emerald-50 text-emerald-800 border-emerald-200',
                        'partial' => 'bg-amber-50 text-amber-800 border-amber-200',
                        default => 'bg-rose-50 text-rose-800 border-rose-200',
                    };
                @endphp
                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold border {{ $badgeClass }} uppercase">
                    {{ $invoice->status }}
                </span>
            </div>
        </div>

        {{-- Invoice Document Card --}}
        <div class="bg-white rounded-2xl border border-zinc-200/90 shadow-md p-8 sm:p-12 text-zinc-800 text-xs leading-relaxed relative overflow-hidden" id="printable-invoice">
            {{-- Company Logo Watermark --}}
            <div class="absolute inset-0 flex items-center justify-center pointer-events-none select-none z-0 overflow-hidden">
                <img src="{{ asset('images/logo.png') }}" alt="" class="w-96 max-w-[65%] opacity-[0.04] object-contain grayscale transform -rotate-12">
            </div>

            {{-- Header --}}
            <div class="relative z-10 flex items-start justify-between pb-8 border-b border-zinc-100">
                <div class="flex items-center space-x-4">
                    <img src="{{ asset('images/logo.png') }}" alt="Anagkazo Autoparts" class="h-14 sm:h-16 w-auto object-contain">
                    <div>
                        <span class="font-extrabold text-lg tracking-tight text-zinc-950 block">ANAGKAZO AUTOPARTS</span>
                        <span class="text-xs text-zinc-400 font-medium uppercase tracking-wider">Kariakoo Tyre Wholesalers & Distributors</span>
                    </div>
                </div>

                <div class="text-right">
                    @if($invoice->tax_type === 'exclusive')
                        <span class="inline-flex items-center px-2.5 py-1 rounded-md text-[11px] font-black uppercase tracking-wider bg-amber-100 text-amber-950 border border-amber-300 mb-1">
                            TAX EXCLUSIVE INVOICE
                        </span>
                    @else
                        <span class="text-xs font-bold uppercase tracking-widest text-[#1e3a8a] block">TAX INVOICE</span>
                    @endif
                    <span class="text-sm font-mono font-bold text-zinc-950 block">{{ $invoice->invoice_number }}</span>
                </div>
            </div>

            {{-- Metadata Row --}}
            <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 py-6 border-b border-zinc-100">
                <div>
                    <span class="text-zinc-400 block text-[11px]">Issue Date</span>
                    <span class="font-semibold text-zinc-900 text-xs">{{ $invoice->issue_date ? $invoice->issue_date->format('d F Y') : '-' }}</span>
                </div>
                <div>
                    <span class="text-zinc-400 block text-[11px]">Due Date</span>
                    <span class="font-semibold text-zinc-900 text-xs">{{ $invoice->due_date ? $invoice->due_date->format('d F Y') : '-' }}</span>
                </div>
                <div>
                    <span class="text-zinc-400 block text-[11px]">Payment Terms</span>
                    <span class="font-semibold text-zinc-900 text-xs">{{ $invoice->payment_terms }}</span>
                </div>
                <div>
                    <span class="text-zinc-400 block text-[11px]">Recorded / Added</span>
                    <span class="font-semibold text-zinc-900 text-xs">{{ $invoice->created_at ? $invoice->created_at->format('d M Y, h:i A') : '-' }}</span>
                </div>
            </div>

            {{-- Well-Arranged Side-by-Side Address Grid --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6 py-6 border-b border-zinc-100">
                <div class="p-4 rounded-xl bg-zinc-50/70 border border-zinc-200/80">
                    <span class="text-zinc-400 block text-[10px] font-bold uppercase tracking-wider mb-2">Billed By (Supplier / Distributor):</span>
                    <span class="font-bold text-zinc-950 text-sm block">Anagkazo Autoparts Ltd</span>
                    <div class="text-zinc-600 text-xs leading-relaxed mt-1.5 space-y-0.5">
                        <p>Plot 42, Msimbazi & Uhuru Street, Kariakoo</p>
                        <p>P.O. Box 24901, Dar es Salaam, Tanzania</p>
                        <p class="text-zinc-500 font-mono text-[11px]"><strong>TIN:</strong> 188-458-408 | <strong></strong> </p>
                        <p><strong>Tel:</strong> +255 655 552 040 | <strong>Email:</strong> sales@anagkazo.co.tz</p>
                    </div>
                    @if($invoice->issuer_name || $invoice->issuer_phone)
                        <div class="mt-2.5 pt-2 border-t border-zinc-200/60 flex items-center gap-1.5 text-xs text-[#1e3a8a] font-medium">
                            <span>Issued by:</span>
                            @if($invoice->issuer_name)
                                <span class="font-bold text-zinc-900">{{ $invoice->issuer_name }}</span>
                            @endif
                            @if($invoice->issuer_name && $invoice->issuer_phone)
                                <span class="text-zinc-300">•</span>
                            @endif
                            @if($invoice->issuer_phone)
                                <span class="text-zinc-600 font-mono">{{ $invoice->issuer_phone }}</span>
                            @endif
                        </div>
                    @endif
                </div>

                <div class="p-4 rounded-xl bg-zinc-50/70 border border-zinc-200/80">
                    <span class="text-zinc-400 block text-[10px] font-bold uppercase tracking-wider mb-2">Billed To (Customer / Client):</span>
                    <div class="flex items-center gap-2 flex-wrap mb-1.5">
                        <span class="font-bold text-zinc-950 text-sm">{{ $invoice->customer_name ?: ($invoice->customer?->name ?? 'Valued Customer') }}</span>
                        @if($invoice->customer_tier === 'premium')
                            <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-md text-[10px] font-black bg-amber-100 text-amber-950 border border-amber-300 shadow-2xs">
                                <x-lucide name="crown" class="w-3.5 h-3.5 text-amber-600 fill-amber-500" />
                                Premium Customer
                            </span>
                        @elseif($invoice->customer_tier === 'medium')
                            <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-md text-[10px] font-black bg-blue-100 text-blue-950 border border-blue-300 shadow-2xs">
                                <x-lucide name="award" class="w-3.5 h-3.5 text-blue-600" />
                                Medium Tier
                            </span>
                        @else
                            <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-md text-[10px] font-semibold bg-zinc-100 text-zinc-700 border border-zinc-300 shadow-2xs">
                                <x-lucide name="tag" class="w-3.5 h-3.5 text-zinc-500" />
                                Standard
                            </span>
                        @endif
                    </div>
                    <div class="text-zinc-600 text-xs leading-relaxed whitespace-pre-line space-y-0.5">
                        {!! nl2br(e($invoice->billing_address)) !!}
                        @if($invoice->customer?->phone && !str_contains($invoice->billing_address, $invoice->customer->phone))
                            <p class="mt-1"><strong>Phone:</strong> {{ $invoice->customer->phone }}</p>
                        @endif
                        @if($invoice->customer?->tin_number && !str_contains($invoice->billing_address, $invoice->customer->tin_number))
                            <p><strong>TIN:</strong> {{ $invoice->customer->tin_number }}</p>
                        @endif
                    </div>
                </div>
            </div>

            {{-- Items Table --}}
            <div class="py-6 border-b border-zinc-100">
                <table class="w-full text-left">
                    <thead>
                        <tr class="text-zinc-400 text-[11px] font-semibold border-b border-zinc-100 pb-2">
                            <th class="pb-3">Item Description</th>
                            <th class="pb-3 text-center">Qty</th>
                            <th class="pb-3 text-right">Unit Price (TZS)</th>
                            <th class="pb-3 text-right">Total Amount (TZS)</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-zinc-100">
                        @foreach($invoice->items as $item)
                            <tr>
                                <td class="py-4">
                                    <span class="font-semibold text-zinc-900 block text-xs">{{ $item->item_description }}</span>
                                    <span class="text-[11px] text-zinc-400 font-mono">{{ $item->unit_label ?? 'tyres' }}</span>
                                </td>
                                <td class="py-4 text-center font-mono font-medium text-xs">{{ $item->quantity }}</td>
                                <td class="py-4 text-right font-mono text-xs">{{ number_format($item->unit_price_tzs) }}</td>
                                <td class="py-4 text-right font-mono font-bold text-xs text-zinc-950">{{ number_format($item->total_price_tzs) }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            {{-- Settlement & Totals Grid --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-8 pt-6">
                <div class="text-xs text-zinc-600 space-y-2">
                    <span class="text-zinc-400 block text-[11px] font-semibold uppercase tracking-wider">Settlement & Payment Channels</span>
                    @php
                        $paymentMethodsList = $invoice->payment_methods_list;
                    @endphp
                    <div class="space-y-2.5">
                        @forelse($paymentMethodsList as $pm)
                            <div class="flex items-center gap-3.5 p-2.5 rounded-xl bg-zinc-50 border border-zinc-100 hover:border-zinc-200 transition">
                                @if($pm->logo_url)
                                    <div class="w-11 h-11 rounded-xl bg-white border border-zinc-200/90 p-1 flex items-center justify-center shrink-0 shadow-2xs">
                                        <img src="{{ $pm->logo_url }}" alt="{{ $pm->name }}" class="max-h-full max-w-full object-contain">
                                    </div>
                                @else
                                    <div class="w-11 h-11 rounded-xl bg-blue-50 text-[#1e3a8a] border border-blue-100 flex items-center justify-center font-bold shrink-0">
                                        <x-lucide name="{{ $pm->type === 'mobile_money' ? 'phone' : 'building-2' }}" class="w-5 h-5" />
                                    </div>
                                @endif
                                <div class="leading-tight text-xs">
                                    <span class="font-bold text-zinc-950 block text-[13px]">{{ $pm->name }}</span>
                                    <span class="text-zinc-500 font-mono text-[11px] mt-0.5 block">{{ $pm->type === 'mobile_money' ? 'Till' : 'A/C' }}: <strong class="text-zinc-950 font-semibold">{{ $pm->account_number_or_till }}</strong> ({{ $pm->account_name }})</span>
                                </div>
                            </div>
                        @empty
                            <div>
                                <span class="font-medium text-zinc-800">CRDB Bank (Kariakoo Branch)</span><br>
                                Account: <span class="font-mono font-bold">0150294827100</span>
                            </div>
                        @endforelse
                    </div>
                </div>

                <div class="space-y-2 text-right text-xs">
                    <div class="flex justify-between text-zinc-500">
                        <span>Subtotal</span>
                        <span class="font-mono text-zinc-800">TZS {{ number_format($invoice->subtotal_tzs) }}</span>
                    </div>
                    @if($invoice->discount_tzs > 0)
                        <div class="flex justify-between text-zinc-500">
                            <span>Discount</span>
                            <span class="font-mono text-zinc-800">-TZS {{ number_format($invoice->discount_tzs) }}</span>
                        </div>
                    @endif
                    <div class="flex justify-between text-zinc-500">
                        @if($invoice->tax_type === 'inclusive')
                            <span>TAX ({{ (float) $invoice->tax_rate_percent }}%)</span>
                            <span class="font-mono text-zinc-800">+TZS {{ number_format($invoice->tax_amount_tzs) }}</span>
                        @else
                            <span class="font-bold text-amber-900">TAX Exclusive (0%)</span>
                            <span class="font-bold text-zinc-600">TZS 0</span>
                        @endif
                    </div>
                    <div class="flex justify-between text-zinc-950 font-extrabold pt-2 border-t border-zinc-200 text-sm">
                        <span>Total Amount</span>
                        <span class="font-mono text-zinc-950">TZS {{ number_format($invoice->total_amount_tzs) }}</span>
                    </div>
                </div>
            </div>

            {{-- Notes & Managing Director Signature --}}
            <div class="mt-8 pt-6 border-t border-zinc-100 flex flex-col sm:flex-row sm:items-end justify-between gap-6">
                <div class="text-xs text-zinc-500 leading-relaxed max-w-md">
                    @if ($invoice->notes)
                        <span class="font-semibold text-zinc-700 block mb-1">Terms & Notes:</span>
                        {{ $invoice->notes }}
                    @else
                        <span class="font-semibold text-zinc-700 block mb-1">Terms & Conditions:</span>
                        Goods once sold in good condition are covered under manufacturer mileage warranty. Kariakoo central depot, Dar es Salaam.
                    @endif
                </div>

                <div class="relative text-right flex flex-col items-end shrink-0 min-w-[180px]">
                    <span class="text-[10px] text-zinc-400 font-semibold uppercase tracking-wider block mb-0.5 z-10">Approved & Authorized by:</span>
                    <div class="relative flex items-center justify-center my-0.5">
                        <img 
                            src="{{ asset('images/official-stamp.png') }}" 
                            alt="Anagkazo Official Stamp" 
                            class="w-24 h-24 sm:w-28 sm:h-28 object-contain opacity-90 absolute -top-5 pointer-events-none transform -rotate-6 select-none"
                        >
                        <div class="font-pinyon-script text-sm text-[#0a192f] font-normal leading-none py-1 select-none z-10">
                            Joseph Matemba
                        </div>
                    </div>
                    <div class="w-44 border-b border-zinc-800 my-1 z-10"></div>
                    <span class="text-[10px] text-zinc-500 font-semibold uppercase tracking-wider block z-10">Managing Director</span>
                </div>
            </div>
        </div>

        {{-- Bottom Navigation Bar --}}
        <div class="flex flex-col sm:flex-row items-center justify-between gap-3 p-4 bg-white rounded-2xl border border-zinc-200/90 shadow-xs">
            <a href="{{ route('invoices.create') }}" class="inline-flex items-center px-4 py-2 text-xs font-bold text-white bg-[#1e3a8a] hover:bg-[#1d4ed8] rounded-xl shadow-xs transition">
                <x-lucide name="plus-circle" class="w-3.5 h-3.5 mr-1.5 text-blue-300" />
                Return to Invoice Section (Create New Invoice)
            </a>
            @if(auth()->user()?->isAdmin())
                <a href="{{ route('invoices.index') }}" class="inline-flex items-center px-4 py-2 text-xs font-semibold text-zinc-700 bg-zinc-100 hover:bg-zinc-200 rounded-xl transition">
                    <x-lucide name="list" class="w-3.5 h-3.5 mr-1.5 text-zinc-500" />
                    Back to All Records
                </a>
            @endif
        </div>
    </main>
</body>
</html>
