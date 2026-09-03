<div wire:poll.3s class="min-h-screen bg-[#f3f4f6] text-zinc-900 py-6 px-4 sm:px-6 lg:px-8 font-sans antialiased">
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

    <main class="max-w-7xl mx-auto space-y-6">
        @if (session()->has('success'))
            <div class="p-4 rounded-xl bg-blue-50 border border-blue-200 text-blue-900 flex items-center gap-3 animate-slide-down shadow-xs">
                <x-lucide name="check-circle-2" class="w-5 h-5 text-blue-600 shrink-0" />
                <span class="text-sm font-medium">{{ session('success') }}</span>
            </div>
        @endif

        @if (session()->has('error'))
            <div class="p-4 rounded-xl bg-rose-50 border border-rose-200 text-rose-900 flex items-center gap-3 animate-slide-down shadow-xs">
                <x-lucide name="alert-triangle" class="w-5 h-5 text-rose-600 shrink-0" />
                <span class="text-sm font-medium">{{ session('error') }}</span>
            </div>
        @endif

        {{-- Metrics Summary Cards --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
            {{-- 1. Total Invoiced --}}
            <div class="bg-white rounded-2xl p-5 border border-zinc-200/90 shadow-xs card-interactive">
                <div class="flex items-center justify-between text-zinc-500 mb-2">
                    <span class="text-xs font-bold uppercase tracking-wider">Total Invoiced</span>
                    <x-lucide name="file-text" class="w-4 h-4 text-zinc-400" />
                </div>
                <div class="text-xl font-black text-[#0a192f] font-mono">TZS {{ number_format($totalIssued) }}</div>
                <div class="flex items-center justify-between text-[11px] text-zinc-400 mt-1">
                    <span>All Historical Records</span>
                    <span class="font-bold text-zinc-600">{{ $totalCount }} total</span>
                </div>
            </div>

            {{-- 2. Total Paid --}}
            <div class="bg-white rounded-2xl p-5 border border-emerald-200/80 bg-emerald-50/20 shadow-xs card-interactive">
                <div class="flex items-center justify-between text-emerald-700 mb-2">
                    <span class="text-xs font-bold uppercase tracking-wider">Paid & Settled</span>
                    <x-lucide name="check-circle-2" class="w-4 h-4 text-emerald-600" />
                </div>
                <div class="text-xl font-black text-emerald-700 font-mono">TZS {{ number_format($totalPaid) }}</div>
                <div class="flex items-center justify-between text-[11px] text-emerald-600 mt-1">
                    <span>Cleared Bank / M-Pesa</span>
                    <span class="font-bold">{{ $paidCount }} cleared</span>
                </div>
            </div>

            {{-- 3. Total Pending --}}
            <div class="bg-white rounded-2xl p-5 border border-blue-200/80 bg-blue-50/20 shadow-xs card-interactive">
                <div class="flex items-center justify-between text-blue-800 mb-2">
                    <span class="text-xs font-bold uppercase tracking-wider">Pending (Within Terms)</span>
                    <x-lucide name="clock" class="w-4 h-4 text-blue-600" />
                </div>
                <div class="text-xl font-black text-blue-800 font-mono">TZS {{ number_format($totalPending) }}</div>
                <div class="flex items-center justify-between text-[11px] text-blue-700 mt-1">
                    <span>Awaiting Payment</span>
                    <span class="font-bold">{{ $pendingCount }} pending</span>
                </div>
            </div>

            {{-- 4. Total Overdue --}}
            <div class="bg-white rounded-2xl p-5 border border-rose-200/80 bg-rose-50/20 shadow-xs card-interactive">
                <div class="flex items-center justify-between text-rose-700 mb-2">
                    <span class="text-xs font-bold uppercase tracking-wider">Overdue Debt</span>
                    <x-lucide name="alert-triangle" class="w-4 h-4 text-rose-600" />
                </div>
                <div class="text-xl font-black text-rose-700 font-mono">TZS {{ number_format($totalOverdue) }}</div>
                <div class="flex items-center justify-between text-[11px] text-rose-600 mt-1">
                    <span>Past Due Date</span>
                    <span class="font-bold">{{ $overdueCount }} overdue</span>
                </div>
            </div>
        </div>

        {{-- Table Container --}}
        <div class="bg-white rounded-2xl border border-zinc-200/90 shadow-xs overflow-hidden">
            {{-- Toolbar with Search & Quick Status Filters --}}
            <div class="p-5 border-b border-zinc-100 flex flex-col md:flex-row md:items-center justify-between gap-4">
                <div class="relative w-full md:w-80">
                    <x-lucide name="search" class="w-4 h-4 text-zinc-400 absolute left-3.5 top-1/2 -translate-y-1/2" />
                    <input 
                        type="text" 
                        wire:model.live.debounce.300ms="search" 
                        placeholder="Search invoice #, customer name..." 
                        class="w-full text-xs pl-9 pr-4 py-2 rounded-xl border border-zinc-200 text-zinc-900 focus:border-[#1e3a8a] focus:ring-0"
                    />
                </div>

                {{-- Status Filter Tabs / Pills --}}
                <div class="flex flex-wrap items-center gap-1.5">
                    <button 
                        type="button" 
                        wire:click="$set('statusFilter', 'all')" 
                        class="px-3 py-1.5 rounded-lg text-xs font-semibold transition cursor-pointer {{ $statusFilter === 'all' ? 'bg-[#0a192f] text-white shadow-xs' : 'bg-zinc-100 text-zinc-600 hover:bg-zinc-200' }}"
                    >
                        All ({{ $totalCount }})
                    </button>
                    <button 
                        type="button" 
                        wire:click="$set('statusFilter', 'paid')" 
                        class="px-3 py-1.5 rounded-lg text-xs font-semibold transition cursor-pointer {{ $statusFilter === 'paid' ? 'bg-emerald-600 text-white shadow-xs' : 'bg-emerald-50 text-emerald-700 hover:bg-emerald-100' }}"
                    >
                        Paid ({{ $paidCount }})
                    </button>
                    <button 
                        type="button" 
                        wire:click="$set('statusFilter', 'pending')" 
                        class="px-3 py-1.5 rounded-lg text-xs font-semibold transition cursor-pointer {{ $statusFilter === 'pending' ? 'bg-[#1e3a8a] text-white shadow-xs' : 'bg-blue-50 text-blue-800 hover:bg-blue-100' }}"
                    >
                        Pending ({{ $pendingCount }})
                    </button>
                    <button 
                        type="button" 
                        wire:click="$set('statusFilter', 'overdue')" 
                        class="px-3 py-1.5 rounded-lg text-xs font-semibold transition cursor-pointer {{ $statusFilter === 'overdue' ? 'bg-rose-600 text-white shadow-xs' : 'bg-rose-50 text-rose-700 hover:bg-rose-100' }}"
                    >
                        Overdue ({{ $overdueCount }})
                    </button>
                </div>
            </div>

            {{-- Table --}}
            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs">
                    <thead class="bg-zinc-50/70 border-b border-zinc-100 text-[11px] font-semibold text-zinc-500 uppercase tracking-wider">
                        <tr>
                            <th class="px-6 py-3.5">Invoice #</th>
                            <th class="px-6 py-3.5">Customer</th>
                            <th class="px-6 py-3.5">Issue Date</th>
                            <th class="px-6 py-3.5">Due Date</th>
                            <th class="px-6 py-3.5 text-right">Total (TZS)</th>
                            <th class="px-6 py-3.5 text-right">Balance (TZS)</th>
                            <th class="px-6 py-3.5 text-center">Status</th>
                            <th class="px-6 py-3.5 text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-zinc-100">
                        @forelse ($invoices as $inv)
                            <tr class="hover:bg-zinc-50/60 transition">
                                <td class="px-6 py-4 font-mono font-bold text-zinc-900">
                                    <a href="{{ route('invoices.show', $inv) }}" class="hover:text-[#1e3a8a] transition block">
                                        {{ $inv->invoice_number }}
                                    </a>
                                    <span class="text-[10px] text-zinc-400 font-sans font-normal block mt-0.5">
                                        Added: {{ $inv->created_at ? $inv->created_at->format('M d, Y, h:i A') : '-' }}
                                    </span>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-1.5 flex-wrap">
                                        <div class="font-semibold text-zinc-900">{{ $inv->customer_name ?: ($inv->customer?->name ?? 'Walk-in Customer') }}</div>
                                        @if($inv->customer_tier === 'premium')
                                            <span class="inline-flex items-center gap-1 px-1.5 py-0.5 rounded text-[9px] font-black bg-amber-100 text-amber-950 border border-amber-300 shadow-2xs">
                                                <x-lucide name="crown" class="w-3 h-3 text-amber-600 fill-amber-500" />
                                                Premium
                                            </span>
                                        @elseif($inv->customer_tier === 'medium')
                                            <span class="inline-flex items-center gap-1 px-1.5 py-0.5 rounded text-[9px] font-bold bg-blue-50 text-blue-800 border border-blue-200">
                                                <x-lucide name="award" class="w-3 h-3 text-blue-600" />
                                                Medium
                                            </span>
                                        @endif
                                    </div>
                                    <div class="text-[11px] text-zinc-400 truncate max-w-xs">{{ $inv->customer?->phone ?? 'Kariakoo' }}</div>
                                </td>
                                <td class="px-6 py-4 text-zinc-600">
                                    {{ $inv->issue_date ? $inv->issue_date->format('d M Y') : '-' }}
                                </td>
                                <td class="px-6 py-4 text-zinc-600">
                                    @if($inv->due_date)
                                        <div class="{{ $inv->payment_status === 'overdue' ? 'text-rose-600 font-semibold' : 'text-zinc-600' }}">
                                            {{ $inv->due_date->format('d M Y') }}
                                        </div>
                                        @if($inv->payment_status === 'overdue')
                                            <span class="text-[10px] text-rose-500 font-medium block">
                                                {{ $inv->days_overdue }} days overdue
                                            </span>
                                        @endif
                                    @else
                                        -
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-right font-mono font-bold text-zinc-950">
                                    {{ number_format((float) ($inv->total_amount_tzs ?? 0)) }}
                                </td>
                                <td class="px-6 py-4 text-right font-mono font-semibold {{ ($inv->balance_tzs ?? 0) > 0 ? 'text-rose-600' : 'text-emerald-600' }}">
                                    {{ number_format((float) ($inv->balance_tzs ?? 0)) }}
                                </td>
                                <td class="px-6 py-4 text-center">
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
                                <td class="px-6 py-4 text-right space-x-1.5">
                                    @if($inv->payment_status !== 'paid')
                                        <button 
                                            type="button" 
                                            wire:click="markAsPaid({{ $inv->id }})" 
                                            class="inline-flex items-center px-2 py-1 text-[11px] font-semibold text-emerald-700 bg-emerald-50 border border-emerald-200 rounded-lg hover:bg-emerald-100 transition cursor-pointer"
                                            title="Mark Invoice as Paid"
                                        >
                                            <x-lucide name="check" class="w-3 h-3 mr-0.5 text-emerald-600" />
                                            Pay
                                        </button>
                                    @endif

                                    <a 
                                        href="{{ route('invoices.show', $inv) }}" 
                                        class="inline-flex items-center px-2.5 py-1 text-xs font-medium text-zinc-700 bg-white border border-zinc-200 rounded-lg hover:bg-zinc-50 transition"
                                        title="View Invoice"
                                    >
                                        <x-lucide name="eye" class="w-3.5 h-3.5 mr-1 text-zinc-500" />
                                        View
                                    </a>

                                    <a 
                                        href="{{ route('invoices.download', $inv) }}" 
                                        class="inline-flex items-center px-2.5 py-1 text-xs font-semibold text-white bg-[#0a192f] hover:bg-[#1e3a8a] rounded-lg transition cursor-pointer"
                                        title="Download PDF to Device"
                                    >
                                        <x-lucide name="download" class="w-3.5 h-3.5 mr-1 text-blue-300" />
                                        PDF
                                    </a>

                                    <a 
                                        href="{{ route('invoices.print', $inv) }}" 
                                        target="_blank" 
                                        class="inline-flex items-center px-2 py-1 text-xs font-medium text-[#1e3a8a] bg-blue-50 border border-blue-200 rounded-lg hover:bg-blue-100 transition"
                                        title="Print Invoice"
                                    >
                                        <x-lucide name="printer" class="w-3.5 h-3.5" />
                                    </a>

                                    @if(auth()->user()?->isAdmin())
                                        <button 
                                            type="button" 
                                            wire:click="confirmDelete({{ $inv->id }})" 
                                            class="inline-flex items-center p-1.5 text-zinc-400 hover:text-rose-600 hover:bg-rose-50 rounded-lg transition cursor-pointer"
                                            title="Delete Invoice"
                                        >
                                            <x-lucide name="trash-2" class="w-3.5 h-3.5" />
                                        </button>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="px-6 py-12 text-center text-zinc-400">
                                    <x-lucide name="inbox" class="w-8 h-8 mx-auto mb-2 text-zinc-300" />
                                    No invoices found matching the current filter.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="p-4 border-t border-zinc-100">
                {{ $invoices->links() }}
            </div>
        </div>

        {{-- DELETE CONFIRMATION MODAL --}}
        @if ($showDeleteModal)
            <div class="fixed inset-0 bg-black/60 backdrop-blur-xs z-50 flex items-center justify-center p-4 animate-fade-in">
                <div class="bg-white rounded-2xl max-w-md w-full p-6 shadow-2xl border border-zinc-200 animate-scale-up">
                    <div class="w-12 h-12 rounded-full bg-rose-50 text-rose-600 flex items-center justify-center mx-auto mb-4 border border-rose-100">
                        <x-lucide name="alert-triangle" class="w-6 h-6" />
                    </div>

                    <h3 class="text-center text-lg font-bold text-zinc-950">Confirm Invoice Deletion</h3>
                    <p class="text-center text-xs text-zinc-500 mt-2 leading-relaxed">
                        Are you sure you want to delete invoice <strong class="text-zinc-800">{{ $deletingInvoiceNumber }}</strong>? This record will be permanently deleted from the system.
                    </p>

                    <div class="mt-6 flex items-center justify-center space-x-3">
                        <button 
                            type="button" 
                            wire:click="cancelDelete" 
                            class="px-4 py-2 text-xs font-semibold text-zinc-700 bg-zinc-100 hover:bg-zinc-200 rounded-xl transition cursor-pointer btn-interactive"
                        >
                            Cancel
                        </button>
                        <button 
                            type="button" 
                            wire:click="deleteInvoice" 
                            class="px-5 py-2 text-xs font-bold text-white bg-rose-600 hover:bg-rose-700 rounded-xl transition cursor-pointer shadow-xs btn-interactive"
                        >
                            Yes, Delete Invoice
                        </button>
                    </div>
                </div>
            </div>
        @endif
    </main>

    <x-page-footer />
</div>
