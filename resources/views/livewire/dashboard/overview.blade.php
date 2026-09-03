<div wire:poll.4s class="min-h-screen bg-[#f3f4f6] text-zinc-900 py-6 px-4 sm:px-6 lg:px-8 font-sans antialiased">
    {{-- Global Top Navigation Bar with Deep Navy Styling --}}
    <header class="sticky top-4 z-40 max-w-7xl mx-auto mb-8 bg-white/95 backdrop-blur-md border border-zinc-200/90 rounded-2xl px-5 py-2.5 shadow-md flex items-center justify-between gap-4 transition-all duration-200">
        {{-- Left: Brand & Main Navigation --}}
        <div class="flex items-center space-x-4 lg:space-x-6 shrink-0">
            <a href="{{ route('dashboard') }}" class="flex items-center space-x-2.5 shrink-0 group">
                <img src="{{ asset('images/logo.png') }}" alt="Anagkazo Logo" class="h-8 w-auto object-contain shrink-0">
                <div class="whitespace-nowrap">
                    <span class="font-extrabold text-sm tracking-tight text-zinc-900 block leading-tight">ANAGKAZO AUTOPARTS</span>
                    <span class="text-[10px] text-zinc-400 font-semibold uppercase tracking-wider block">Kariakoo, DSM</span>
                </div>
            </a>

            <nav class="hidden md:flex items-center space-x-1 shrink-0">
                @if(auth()->user()?->isAdmin())
                    <a href="{{ route('dashboard') }}" wire:navigate class="whitespace-nowrap px-3 py-1.5 text-xs font-semibold rounded-xl transition bg-[#0a192f] text-white shadow-xs">Dashboard</a>
                @endif
                <a href="{{ route('invoices.create') }}" wire:navigate class="whitespace-nowrap px-3 py-1.5 text-xs font-semibold rounded-xl transition text-zinc-600 hover:text-zinc-900 hover:bg-zinc-100">Invoice</a>
                @if(auth()->user()?->isAdmin())
                    <a href="{{ route('invoices.index') }}" wire:navigate class="whitespace-nowrap px-3 py-1.5 text-xs font-semibold rounded-xl transition text-zinc-600 hover:text-zinc-900 hover:bg-zinc-100">Records</a>
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
                @else
                    <a href="{{ route('login') }}" class="inline-flex items-center gap-1.5 whitespace-nowrap px-3.5 py-1.5 text-xs font-semibold text-zinc-800 hover:text-white bg-zinc-100 hover:bg-[#0a192f] border border-zinc-200 rounded-xl transition cursor-pointer shadow-2xs">
                        <x-lucide name="log-in" class="w-3.5 h-3.5" />
                        <span>Log in</span>
                    </a>
                @endauth
            </div>
        </div>
    </header>

    <main class="max-w-7xl mx-auto space-y-8">
        {{-- Flash Alerts --}}
        @if (session()->has('success'))
            <div class="p-4 bg-emerald-50 border border-emerald-200 text-emerald-800 rounded-2xl flex items-center justify-between shadow-xs">
                <div class="flex items-center space-x-3">
                    <x-lucide name="check-circle-2" class="w-5 h-5 text-emerald-600 shrink-0" />
                    <span class="text-xs font-semibold">{{ session('success') }}</span>
                </div>
            </div>
        @endif

        @if (session()->has('error'))
            <div class="p-4 bg-rose-50 border border-rose-200 text-rose-800 rounded-2xl flex items-center justify-between shadow-xs">
                <div class="flex items-center space-x-3">
                    <x-lucide name="alert-circle" class="w-5 h-5 text-rose-600 shrink-0" />
                    <span class="text-xs font-semibold">{{ session('error') }}</span>
                </div>
            </div>
        @endif

        {{-- Banner / Hero Header with Deep Navy Gradient --}}
        <div class="relative overflow-hidden bg-gradient-to-r from-[#0a192f] via-[#0f284e] to-[#1e3a8a] rounded-3xl p-8 text-white shadow-md">
            <div class="relative z-10 max-w-2xl space-y-3">
                <div class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-blue-500/20 border border-blue-400/30 text-blue-300 text-xs font-semibold">
                    <x-lucide name="disc" class="w-3.5 h-3.5" />
                    Kariakoo Central Depot | Msimbazi Street
                </div>
                <h1 class="text-3xl sm:text-4xl font-black tracking-tight text-white">Commercial & Passenger Tyre Billing Hub</h1>
                <p class="text-sm text-blue-100/90 leading-relaxed">
                    Generate instant tax invoices for wholesale fleet operators, transit garages, and walk-in customers across Dar es Salaam with real-time 18% TRA VAT and mobile money reconciliation.
                </p>
                <div class="pt-2 flex flex-wrap items-center gap-3">
                    <a href="{{ route('invoices.create') }}" class="inline-flex items-center px-5 py-2.5 rounded-xl bg-blue-600 hover:bg-blue-500 text-white font-bold text-sm shadow-sm transition active:scale-95">
                        <x-lucide name="receipt" class="w-4 h-4 mr-2" />
                        Launch Live Invoice Builder
                    </a>
                    <a href="{{ route('reports.index') }}" class="inline-flex items-center px-5 py-2.5 rounded-xl bg-white/10 hover:bg-white/20 text-white font-semibold text-sm border border-white/20 transition">
                        <x-lucide name="bar-chart-3" class="w-4 h-4 mr-2 text-blue-300" />
                        Reports & Analytics
                    </a>
                    @if(auth()->user()?->isAdmin())
                        <a href="{{ route('security-credentials.index') }}" wire:navigate class="inline-flex items-center px-4 py-2.5 rounded-xl bg-slate-900/60 hover:bg-slate-900/90 text-white font-semibold text-xs border border-white/20 transition">
                            <x-lucide name="shield-check" class="w-4 h-4 mr-1.5 text-blue-300" />
                            Security Credentials
                        </a>
                        <button type="button" wire:click="openResetDataModal" class="inline-flex items-center px-4 py-2.5 rounded-xl bg-rose-950/60 hover:bg-rose-900/90 text-rose-200 hover:text-white font-semibold text-xs border border-rose-500/30 transition cursor-pointer">
                            <x-lucide name="trash-2" class="w-4 h-4 mr-1.5 text-rose-400" />
                            Wipe Operational Data
                        </button>
                    @endif
                </div>
            </div>

            {{-- Background Radial Pattern / Silhouette --}}
            <div class="absolute right-0 top-0 bottom-0 w-1/3 opacity-15 pointer-events-none hidden md:flex items-center justify-center">
                <x-lucide name="disc" class="w-80 h-80 text-white animate-spin-slow" />
            </div>
        </div>

        {{-- 4-Grid Key Metrics --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
            {{-- Metric 1: Total Invoiced --}}
            <div class="bg-white rounded-2xl p-6 border border-zinc-200/90 shadow-xs">
                <div class="flex items-center justify-between text-zinc-500 mb-2">
                    <span class="text-xs font-bold uppercase tracking-wider">Total Sales Invoiced</span>
                    <div class="w-8 h-8 rounded-lg bg-blue-50 flex items-center justify-center text-[#1e3a8a]">
                        <x-lucide name="file-text" class="w-4 h-4" />
                    </div>
                </div>
                <div class="text-2xl font-black text-[#0a192f] font-mono tracking-tight">TZS {{ number_format($totalIssuedAmount) }}</div>
                <div class="text-[11px] text-zinc-400 mt-1 flex items-center justify-between">
                    <span>{{ $totalInvoicesCount }} Invoices Generated</span>
                    <a href="{{ route('customers.index') }}" wire:navigate class="text-emerald-600 font-semibold hover:underline flex items-center gap-0.5">
                        {{ $totalCustomers }} Customers &rarr;
                    </a>
                </div>
            </div>

            {{-- Metric 2: Collections Cleared --}}
            <div class="bg-white rounded-2xl p-6 border border-emerald-200/80 bg-emerald-50/20 shadow-xs">
                <div class="flex items-center justify-between text-emerald-700 mb-2">
                    <span class="text-xs font-bold uppercase tracking-wider">Paid & Cleared</span>
                    <div class="w-8 h-8 rounded-lg bg-emerald-100/70 flex items-center justify-center text-emerald-600">
                        <x-lucide name="check-circle-2" class="w-4 h-4" />
                    </div>
                </div>
                <div class="text-2xl font-black text-emerald-700 font-mono tracking-tight">TZS {{ number_format($totalPaidAmount) }}</div>
                <div class="text-[11px] text-emerald-600 mt-1 flex items-center justify-between">
                    <span>Bank & Lipa Namba Receipts</span>
                    <span class="font-bold">{{ $paidCount }} cleared</span>
                </div>
            </div>

            {{-- Metric 3: Pending Debt --}}
            <div class="bg-white rounded-2xl p-6 border border-blue-200/80 bg-blue-50/20 shadow-xs">
                <div class="flex items-center justify-between text-blue-800 mb-2">
                    <span class="text-xs font-bold uppercase tracking-wider">Pending (Within Terms)</span>
                    <div class="w-8 h-8 rounded-lg bg-blue-100 flex items-center justify-center text-blue-800">
                        <x-lucide name="clock" class="w-4 h-4" />
                    </div>
                </div>
                <div class="text-2xl font-black text-blue-800 font-mono tracking-tight">TZS {{ number_format($totalPendingAmount) }}</div>
                <div class="text-[11px] text-blue-700 mt-1 flex items-center justify-between">
                    <span>Active garage trade credit</span>
                    <span class="font-bold">{{ $pendingCount }} pending</span>
                </div>
            </div>

            {{-- Metric 4: Overdue Debt --}}
            <div class="bg-white rounded-2xl p-6 border border-rose-200/80 bg-rose-50/20 shadow-xs">
                <div class="flex items-center justify-between text-rose-700 mb-2">
                    <span class="text-xs font-bold uppercase tracking-wider">Overdue Debt Alert</span>
                    <div class="w-8 h-8 rounded-lg bg-rose-100 flex items-center justify-center text-rose-600">
                        <x-lucide name="alert-triangle" class="w-4 h-4" />
                    </div>
                </div>
                <div class="text-2xl font-black text-rose-700 font-mono tracking-tight">TZS {{ number_format($totalOverdueAmount) }}</div>
                <div class="text-[11px] text-rose-600 mt-1 flex items-center justify-between">
                    <span>Past invoice due date</span>
                    <span class="font-bold">{{ $overdueCount }} overdue</span>
                </div>
            </div>
        </div>

        {{-- Tyre Categories Performance Cards --}}
        <div class="space-y-4">
            <div class="flex items-center justify-between">
                <div>
                    <h2 class="text-lg font-bold text-zinc-950 tracking-tight">Product Categories & Kariakoo Stock</h2>
                    <p class="text-xs text-zinc-500">Live inventory breakdown across commercial, 4x4, and passenger profiles.</p>
                </div>
                <a href="{{ route('products.index') }}" wire:navigate class="text-xs text-[#1e3a8a] font-semibold hover:underline">
                    {{ count($allProducts) }} Active Tyre &rarr;
                </a>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                @foreach ($categoryBreakdown as $catKey => $cat)
                    <a 
                        href="{{ route('products.index', ['categoryFilter' => $cat['name']]) }}" 
                        wire:navigate 
                        class="bg-white rounded-2xl border border-zinc-200/90 p-6 shadow-xs flex flex-col justify-between space-y-4 hover:border-blue-500 hover:shadow-md transition group block cursor-pointer"
                        title="View {{ $cat['name'] }} Tyres in Stock"
                    >
                        <div class="flex items-start justify-between">
                            <div class="space-y-1">
                                <span class="text-[10px] font-bold uppercase tracking-wider text-[#1e3a8a]">Category</span>
                                <h3 class="font-bold text-sm text-zinc-900 group-hover:text-[#1e3a8a] transition">{{ $cat['name'] }}</h3>
                                <p class="text-xs text-zinc-400">Popular: {{ $cat['sample_size'] }}</p>
                            </div>
                            <div class="w-10 h-10 rounded-xl bg-blue-50 flex items-center justify-center text-[#1e3a8a] group-hover:bg-blue-600 group-hover:text-white transition">
                                <x-lucide name="{{ $cat['icon'] }}" class="w-5 h-5" />
                            </div>
                        </div>

                        <div class="pt-2 border-t border-zinc-100 flex items-center justify-between">
                            <div>
                                <span class="text-xs text-zinc-500">Depot Stock:</span>
                                <span class="font-bold text-sm text-[#0a192f] font-mono ml-1">{{ $cat['stock'] }} tyres</span>
                            </div>
                            <span class="text-xs font-semibold text-blue-600 group-hover:underline">View {{ $cat['models'] }} Brands &rarr;</span>
                        </div>
                    </a>
                @endforeach
            </div>
        </div>

        {{-- Interactive Product Catalog Gallery --}}
        <div class="space-y-4" id="inventory">
            <div class="flex items-center justify-between">
                <div>
                    <h2 class="text-lg font-bold text-zinc-950 tracking-tight">Depot Inventory Showcase</h2>
                    <p class="text-xs text-zinc-500">Commercial truck radials, SUV all-terrain, and passenger tyres available for direct dispatch.</p>
                </div>
                <a href="{{ route('products.index') }}" wire:navigate class="text-xs font-semibold text-[#1e3a8a] hover:underline transition">
                    Open Stock Manager &rarr;
                </a>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach ($allProducts as $product)
                    <div class="bg-white rounded-2xl border border-zinc-200/90 overflow-hidden shadow-xs hover:shadow-md hover:border-blue-300 transition flex flex-col justify-between group">
                        {{-- Image with badge (clickable to product page) --}}
                        <a 
                            href="{{ route('products.index', ['search' => $product->sku]) }}" 
                            wire:navigate 
                            class="relative h-44 bg-zinc-100 overflow-hidden block cursor-pointer"
                            title="View {{ $product->brand }} {{ $product->size }} in Stock Manager"
                        >
                            <img 
                                src="{{ $product->image_url ?: 'https://images.unsplash.com/photo-1578844251758-2f71da64c96f?w=600&auto=format&fit=crop&q=80' }}" 
                                alt="{{ $product->brand }} {{ $product->size }}" 
                                class="w-full h-full object-cover transition duration-300 group-hover:scale-105"
                            />
                            <div class="absolute top-3 left-3 bg-[#0a192f]/90 backdrop-blur-xs text-white text-[10px] font-bold px-2.5 py-1 rounded-full uppercase tracking-wider">
                                {{ $product->brand }}
                            </div>

                            @if($product->stock_quantity <= $product->reorder_threshold)
                                <div class="absolute top-3 right-3 bg-rose-600 text-white text-[10px] font-bold px-2 py-0.5 rounded-full uppercase tracking-wider flex items-center gap-1">
                                    <x-lucide name="alert-triangle" class="w-3 h-3" />
                                    Low Stock: {{ $product->stock_quantity }}
                                </div>
                            @else
                                <div class="absolute top-3 right-3 bg-emerald-600 text-white text-[10px] font-bold px-2 py-0.5 rounded-full uppercase tracking-wider">
                                    In Stock: {{ $product->stock_quantity }}
                                </div>
                            @endif
                        </a>

                        {{-- Details (clickable to product page) --}}
                        <div class="p-5 space-y-3 flex-1 flex flex-col justify-between">
                            <a 
                                href="{{ route('products.index', ['search' => $product->sku]) }}" 
                                wire:navigate 
                                class="block cursor-pointer"
                                title="View {{ $product->brand }} {{ $product->size }} details"
                            >
                                <div class="text-xs font-mono font-bold text-[#1e3a8a]">{{ $product->size }}</div>
                                <h4 class="font-bold text-sm text-zinc-900 group-hover:text-[#1e3a8a] transition mt-0.5">{{ $product->pattern }}</h4>
                                <span class="text-[11px] text-zinc-400 block font-mono mt-0.5">SKU: {{ $product->sku }}</span>
                            </a>

                            <div class="pt-3 border-t border-zinc-100 flex items-center justify-between">
                                <div>
                                    <span class="text-[10px] text-zinc-400 uppercase block">Unit Price (TZS)</span>
                                    <span class="font-bold text-sm font-mono text-zinc-950">{{ number_format($product->unit_price_tzs) }}</span>
                                </div>

                                <div class="flex items-center gap-1.5">
                                    <a 
                                        href="{{ route('products.index', ['search' => $product->sku]) }}" 
                                        wire:navigate 
                                        class="inline-flex items-center px-2.5 py-1.5 text-xs font-semibold text-zinc-700 bg-zinc-100 hover:bg-zinc-200 rounded-lg transition"
                                        title="View Stock"
                                    >
                                        <x-lucide name="package" class="w-3.5 h-3.5 mr-1 text-zinc-500" />
                                        Stock
                                    </a>
                                    <a 
                                        href="{{ route('invoices.create') }}" 
                                        class="inline-flex items-center px-3 py-1.5 text-xs font-semibold text-white bg-[#1e3a8a] hover:bg-blue-700 rounded-lg transition shadow-2xs"
                                        title="Create Invoice for this Tyre"
                                    >
                                        Invoice
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        {{-- Recent Invoices Activity --}}
        <div class="bg-white rounded-2xl border border-zinc-200/90 shadow-xs overflow-hidden">
            <div class="p-5 border-b border-zinc-100 flex items-center justify-between">
                <div>
                    <h3 class="font-bold text-sm text-zinc-900">Recent Invoice Transactions</h3>
                    <p class="text-xs text-zinc-400">Latest commercial and passenger tyre invoices issued in Kariakoo.</p>
                </div>
                <a href="{{ route('invoices.index') }}" wire:navigate class="text-xs font-semibold text-[#1e3a8a] hover:underline transition">
                    View All Invoices &rarr;
                </a>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs">
                    <thead class="bg-zinc-50/70 border-b border-zinc-100 text-[11px] font-semibold text-zinc-500 uppercase tracking-wider">
                        <tr>
                            <th class="px-6 py-3">Invoice #</th>
                            <th class="px-6 py-3">Customer</th>
                            <th class="px-6 py-3">Date</th>
                            <th class="px-6 py-3 text-right">Amount (TZS)</th>
                            <th class="px-6 py-3 text-center">Status</th>
                            <th class="px-6 py-3 text-right">Action</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-zinc-100">
                        @foreach ($recentInvoices as $inv)
                            <tr class="hover:bg-zinc-50/60 transition">
                                <td class="px-6 py-3.5 font-mono font-bold text-zinc-900">
                                    <a href="{{ route('invoices.show', $inv) }}" class="hover:text-[#1e3a8a] hover:underline transition">
                                        {{ $inv->invoice_number }}
                                    </a>
                                </td>
                                <td class="px-6 py-3.5 font-semibold text-zinc-800">
                                    @php
                                        $custName = $inv->customer_name ?: ($inv->customer?->name ?? 'Walk-in Customer');
                                    @endphp
                                    @if($inv->customer_id || $inv->customer)
                                        <a 
                                            href="{{ route('customers.index', ['search' => $inv->customer?->name ?: $inv->customer_name]) }}" 
                                            wire:navigate 
                                            class="hover:text-[#1e3a8a] hover:underline transition inline-flex items-center gap-1 font-bold text-zinc-950"
                                            title="View Customer in Directory"
                                        >
                                            <span>{{ $custName }}</span>
                                            <x-lucide name="external-link" class="w-3 h-3 text-zinc-400 opacity-70" />
                                        </a>
                                    @else
                                        <span class="text-zinc-700">{{ $custName }}</span>
                                    @endif
                                </td>
                                <td class="px-6 py-3.5 text-zinc-500">
                                    {{ $inv->issue_date ? $inv->issue_date->format('d M Y') : '-' }}
                                </td>
                                <td class="px-6 py-3.5 text-right font-mono font-bold text-zinc-950">
                                    {{ number_format($inv->total_amount_tzs) }}
                                </td>
                                <td class="px-6 py-3.5 text-center">
                                    @php
                                        $statusClass = match($inv->payment_status) {
                                            'paid' => 'bg-emerald-50 text-emerald-700 border-emerald-200',
                                            'overdue' => 'bg-rose-50 text-rose-700 border-rose-200 font-bold',
                                            'partial' => 'bg-amber-50 text-amber-700 border-amber-200',
                                            default => 'bg-blue-50 text-blue-800 border-blue-200',
                                        };
                                    @endphp
                                    <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[10px] font-semibold border {{ $statusClass }}">
                                        @if($inv->payment_status === 'paid')
                                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                                            PAID
                                        @elseif($inv->payment_status === 'overdue')
                                            <span class="w-1.5 h-1.5 rounded-full bg-rose-500 animate-pulse"></span>
                                            OVERDUE
                                        @elseif($inv->payment_status === 'partial')
                                            <span class="w-1.5 h-1.5 rounded-full bg-amber-500"></span>
                                            PARTIAL
                                        @else
                                            <span class="w-1.5 h-1.5 rounded-full bg-blue-500"></span>
                                            PENDING
                                        @endif
                                    </span>
                                </td>
                                <td class="px-6 py-3.5 text-right space-x-1">
                                    <a href="{{ route('invoices.show', $inv) }}" class="inline-flex items-center px-2.5 py-1 text-xs font-medium text-zinc-700 bg-white border border-zinc-200 rounded-lg hover:bg-zinc-50 transition">
                                        <x-lucide name="eye" class="w-3.5 h-3.5 mr-1 text-zinc-500" />
                                        View
                                    </a>
                                    <a href="{{ route('invoices.print', $inv) }}" target="_blank" class="inline-flex items-center px-2.5 py-1 text-xs font-medium text-[#1e3a8a] bg-blue-50 border border-blue-200 rounded-lg hover:bg-blue-100 transition" title="Print or Save PDF">
                                        <x-lucide name="printer" class="w-3.5 h-3.5 mr-1 text-[#1e3a8a]" />
                                        Print
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Danger Zone / Database Reset (Admin Only) --}}
        @if(auth()->user()?->isAdmin())
            <div class="bg-gradient-to-r from-rose-50 to-red-50/50 border border-rose-200 rounded-3xl p-6 sm:p-8 shadow-xs">
                <div class="flex flex-col md:flex-row md:items-center justify-between gap-6">
                    <div class="space-y-1.5 max-w-2xl">
                        <div class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-rose-100 text-rose-800 text-[11px] font-bold tracking-wide uppercase">
                            <x-lucide name="alert-triangle" class="w-3.5 h-3.5 text-rose-600" />
                            System Maintenance & Reset
                        </div>
                        <h3 class="text-base sm:text-lg font-bold text-zinc-950">Wipe Operational Database (Clean Slate)</h3>
                        <p class="text-xs text-zinc-600 leading-relaxed">
                            Permanently removes all transactional records (invoices, billing items, customer directory, and depot stock catalog). 
                            <strong class="text-zinc-900 font-semibold">Administrator & Staff login credentials and configured Payment Methods will be safely preserved.</strong>
                        </p>
                    </div>
                    <div class="shrink-0">
                        <button 
                            type="button" 
                            wire:click="openResetDataModal"
                            class="inline-flex items-center justify-center px-5 py-3 rounded-2xl bg-rose-600 hover:bg-rose-700 text-white font-bold text-xs shadow-sm transition active:scale-95 cursor-pointer"
                        >
                            <x-lucide name="trash-2" class="w-4 h-4 mr-2" />
                            Wipe Database Records
                        </button>
                    </div>
                </div>
            </div>
        @endif
    </main>

    {{-- Reset Database Confirmation Modal --}}
    @if ($showResetDataModal)
        <div class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-zinc-950/70 backdrop-blur-xs">
            <div class="bg-white rounded-3xl max-w-lg w-full overflow-hidden shadow-2xl border border-rose-200 animate-in fade-in zoom-in-95 duration-150">
                <div class="bg-[#0a192f] p-6 text-white flex items-center justify-between">
                    <div class="flex items-center space-x-3">
                        <div class="w-10 h-10 rounded-2xl bg-rose-500/20 border border-rose-500/30 flex items-center justify-center text-rose-400">
                            <x-lucide name="alert-triangle" class="w-5 h-5" />
                        </div>
                        <div>
                            <h3 class="text-base font-bold text-white">Wipe All Operational Data</h3>
                            <p class="text-[11px] text-zinc-400">Permanent database reset</p>
                        </div>
                    </div>
                    <button 
                        type="button" 
                        wire:click="cancelResetDataModal"
                        class="p-1 rounded-lg text-zinc-400 hover:text-white hover:bg-white/10 transition cursor-pointer"
                    >
                        <x-lucide name="x" class="w-5 h-5" />
                    </button>
                </div>

                <div class="p-6 space-y-5 text-zinc-800">
                    <div class="p-4 rounded-2xl bg-rose-50 border border-rose-200 space-y-2">
                        <p class="text-xs font-bold text-rose-900">
                            Are you absolutely sure you want to wipe all operational data?
                        </p>
                        <p class="text-[11px] text-rose-700 leading-relaxed">
                            This action is permanent and cannot be reversed.
                        </p>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 text-xs">
                        <div class="p-3.5 rounded-xl bg-zinc-50 border border-zinc-200">
                            <span class="font-bold text-rose-600 block mb-1.5 flex items-center gap-1">
                                <x-lucide name="x-circle" class="w-3.5 h-3.5" /> What gets DELETED:
                            </span>
                            <ul class="space-y-1 text-[11px] text-zinc-600 list-disc list-inside">
                                <li>All Invoices & Items</li>
                                <li>All Tyre Stock & Catalog</li>
                                <li>All Customer Accounts</li>
                            </ul>
                        </div>
                        <div class="p-3.5 rounded-xl bg-emerald-50/50 border border-emerald-200">
                            <span class="font-bold text-emerald-700 block mb-1.5 flex items-center gap-1">
                                <x-lucide name="check-circle" class="w-3.5 h-3.5" /> What is PRESERVED:
                            </span>
                            <ul class="space-y-1 text-[11px] text-zinc-600 list-disc list-inside">
                                <li>Admin & Staff Logins</li>
                                <li>Payment Methods (Banks/Tills)</li>
                                <li>Active Sessions</li>
                            </ul>
                        </div>
                    </div>

                    <div class="space-y-2">
                        <label class="block text-xs font-semibold text-zinc-700">
                            Type <span class="font-mono font-bold text-rose-600">DELETE</span> to confirm:
                        </label>
                        <input 
                            type="text" 
                            wire:model.live="confirmResetText"
                            placeholder="Type DELETE" 
                            class="w-full text-xs px-4 py-2.5 rounded-xl border border-zinc-200 focus:border-rose-600 focus:ring-0 text-zinc-900 font-mono"
                        />
                    </div>

                    <div class="pt-4 border-t border-zinc-100 flex items-center justify-end gap-2">
                        <button 
                            type="button" 
                            wire:click="cancelResetDataModal"
                            class="px-4 py-2.5 rounded-xl text-xs font-bold text-zinc-600 hover:bg-zinc-100 transition cursor-pointer"
                        >
                            Cancel
                        </button>
                        <button 
                            type="button" 
                            wire:click="wipeOperationalData"
                            @if(strtoupper(trim($confirmResetText)) !== 'DELETE') disabled @endif
                            class="px-5 py-2.5 rounded-xl bg-rose-600 hover:bg-rose-700 disabled:opacity-50 disabled:cursor-not-allowed text-white text-xs font-bold transition shadow-xs cursor-pointer inline-flex items-center gap-1.5"
                        >
                            <x-lucide name="trash-2" class="w-3.5 h-3.5" />
                            Permanently Wipe Data
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <x-page-footer />
</div>
