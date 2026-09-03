<div wire:poll.4s class="min-h-screen bg-[#f8fafc] text-zinc-900 antialiased p-4 sm:p-6 lg:p-8">
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
                    <a href="{{ route('invoices.index') }}" wire:navigate class="whitespace-nowrap px-3 py-1.5 text-xs font-semibold rounded-xl transition text-zinc-600 hover:text-zinc-900 hover:bg-zinc-100">Records</a>
                @endif
                <a href="{{ route('products.index') }}" wire:navigate class="whitespace-nowrap px-3 py-1.5 text-xs font-semibold rounded-xl transition text-zinc-600 hover:text-zinc-900 hover:bg-zinc-100">Stocks</a>
                <a href="{{ route('customers.index') }}" wire:navigate class="whitespace-nowrap px-3 py-1.5 text-xs font-semibold rounded-xl transition bg-[#0a192f] text-white shadow-xs">Customers</a>
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
        {{-- Flash Messages --}}
        @if (session()->has('message'))
            <div class="bg-emerald-50 border border-emerald-200 text-emerald-800 px-4 py-3 rounded-xl text-sm flex items-center justify-between shadow-xs animate-fade-in">
                <div class="flex items-center gap-2">
                    <x-lucide name="check-circle-2" class="w-4 h-4 text-emerald-600" />
                    <span>{{ session('message') }}</span>
                </div>
                <button type="button" wire:click="$refresh" class="text-emerald-600 hover:text-emerald-900 font-bold">&times;</button>
            </div>
        @endif

        @if (session()->has('error'))
            <div class="bg-rose-50 border border-rose-200 text-rose-800 px-4 py-3 rounded-xl text-sm flex items-center justify-between shadow-xs animate-fade-in">
                <div class="flex items-center gap-2">
                    <x-lucide name="alert-triangle" class="w-4 h-4 text-rose-600" />
                    <span>{{ session('error') }}</span>
                </div>
                <button type="button" wire:click="$refresh" class="text-rose-600 hover:text-rose-900 font-bold">&times;</button>
            </div>
        @endif

        {{-- Section Title & Top Summary --}}
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <h1 class="text-2xl sm:text-3xl font-extrabold tracking-tight text-zinc-950">Customer Directory & Accounts</h1>
                <p class="text-xs text-zinc-500 mt-0.5">Directory of Retail, Corporate/NGOs, and Government clients.</p>
            </div>

            @if(auth()->user()?->isAdmin())
                <button 
                    type="button" 
                    wire:click="openCreateModal"
                    class="inline-flex items-center px-4 py-2 text-xs font-bold text-white bg-[#0a192f] hover:bg-[#1e3a8a] rounded-xl shadow-sm transition active:scale-95 cursor-pointer btn-interactive self-start sm:self-auto"
                >
                    <x-lucide name="user-plus" class="w-4 h-4 mr-2 text-blue-400" />
                    Register New Customer
                </button>
            @endif
        </div>

        {{-- 4-Card Customer Stats Grid --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
            {{-- Total Customers --}}
            <div class="bg-white rounded-2xl p-5 border border-zinc-200/90 shadow-xs card-interactive">
                <div class="flex items-center justify-between text-zinc-500 mb-2">
                    <span class="text-xs font-bold uppercase tracking-wider text-zinc-500">Total Clients</span>
                    <div class="w-8 h-8 rounded-lg bg-blue-50 flex items-center justify-center text-blue-700">
                        <x-lucide name="users" class="w-4 h-4" />
                    </div>
                </div>
                <div class="text-2xl font-black tracking-tight text-zinc-950">{{ $totalCustomersCount }}</div>
                <p class="text-[11px] text-zinc-500 mt-1">Total registered accounts</p>
            </div>

            {{-- Retail Clients --}}
            <div class="bg-white rounded-2xl p-5 border border-zinc-200/90 shadow-xs card-interactive">
                <div class="flex items-center justify-between text-zinc-500 mb-2">
                    <span class="text-xs font-bold uppercase tracking-wider text-zinc-500">Retail</span>
                    <div class="w-8 h-8 rounded-lg bg-emerald-50 flex items-center justify-center text-emerald-700">
                        <x-lucide name="user-check" class="w-4 h-4" />
                    </div>
                </div>
                <div class="text-2xl font-black tracking-tight text-emerald-950">{{ $retailCount }}</div>
                <p class="text-[11px] text-emerald-600 font-medium mt-1">Retail & individual buyers</p>
            </div>

            {{-- Corporate/NGOs --}}
            <div class="bg-white rounded-2xl p-5 border border-zinc-200/90 shadow-xs card-interactive">
                <div class="flex items-center justify-between text-zinc-500 mb-2">
                    <span class="text-xs font-bold uppercase tracking-wider text-zinc-500">Corporate / NGOs</span>
                    <div class="w-8 h-8 rounded-lg bg-blue-50 flex items-center justify-center text-blue-700">
                        <x-lucide name="building-2" class="w-4 h-4" />
                    </div>
                </div>
                <div class="text-2xl font-black tracking-tight text-blue-950">{{ $corporateNgoCount }}</div>
                <p class="text-[11px] text-blue-600 font-medium mt-1">Private enterprises & NGO fleets</p>
            </div>

            {{-- Government --}}
            <div class="bg-white rounded-2xl p-5 border border-zinc-200/90 shadow-xs card-interactive">
                <div class="flex items-center justify-between text-zinc-500 mb-2">
                    <span class="text-xs font-bold uppercase tracking-wider text-zinc-500">Government</span>
                    <div class="w-8 h-8 rounded-lg bg-purple-50 flex items-center justify-center text-purple-700">
                        <x-lucide name="landmark" class="w-4 h-4" />
                    </div>
                </div>
                <div class="text-2xl font-black tracking-tight text-purple-950">{{ $governmentCount }}</div>
                <p class="text-[11px] text-purple-600 font-medium mt-1">Ministries, agencies & parastatals</p>
            </div>
        </div>

        {{-- Toolbar: Search, Type Filter & Sorting --}}
        <div class="bg-white p-4 rounded-2xl border border-zinc-200/90 shadow-xs flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div class="flex flex-col sm:flex-row sm:items-center gap-3 flex-1">
                {{-- Search Box --}}
                <div class="relative flex-1 max-w-md">
                    <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-zinc-400">
                        <x-lucide name="search" class="w-4 h-4" />
                    </div>
                    <input 
                        type="text" 
                        wire:model.live.debounce.300ms="search" 
                        placeholder="Search by customer name, contact, phone, TIN..." 
                        class="w-full text-xs rounded-xl border border-zinc-200 pl-9 pr-3.5 py-2 text-zinc-900 bg-white placeholder:text-zinc-400 focus:border-[#1e3a8a] focus:ring-1 focus:ring-[#1e3a8a] transition"
                    />
                </div>

                {{-- Type Filter --}}
                <div class="flex items-center gap-1.5">
                    <span class="text-[11px] font-bold text-zinc-400 uppercase tracking-wider">Category:</span>
                    <select 
                        wire:model.live="typeFilter" 
                        class="text-xs rounded-xl border border-zinc-200 bg-white px-2.5 py-2 text-zinc-800 font-medium focus:border-[#1e3a8a] focus:ring-0"
                    >
                        <option value="all">All Categories</option>
                        <option value="retail">Retail</option>
                        <option value="corporate_ngo">Corporate/NGOs</option>
                        <option value="government">Government</option>
                    </select>
                </div>

                {{-- Tier Filter --}}
                <div class="flex items-center gap-1.5">
                    <span class="text-[11px] font-bold text-zinc-400 uppercase tracking-wider">Tier:</span>
                    <select 
                        wire:model.live="tierFilter" 
                        class="text-xs rounded-xl border border-zinc-200 bg-white px-2.5 py-2 text-zinc-800 font-semibold focus:border-[#1e3a8a] focus:ring-0"
                    >
                        <option value="all">All Tiers ({{ $totalCustomersCount }})</option>
                        <option value="premium">👑 Premium ({{ $premiumCount }})</option>
                        <option value="medium">🥈 Medium ({{ $mediumCount }})</option>
                        <option value="standard">🏷️ Standard ({{ $standardCount }})</option>
                    </select>
                </div>

                {{-- Invoice Status Filter --}}
                <div class="flex items-center gap-1.5">
                    <span class="text-[11px] font-bold text-zinc-400 uppercase tracking-wider">Status:</span>
                    <select 
                        wire:model.live="invoiceStatusFilter" 
                        class="text-xs rounded-xl border border-zinc-200 bg-white px-2.5 py-2 text-zinc-800 font-medium focus:border-[#1e3a8a] focus:ring-0"
                    >
                        <option value="all">All Accounts</option>
                        <option value="has_overdue">With Overdue Debt</option>
                        <option value="has_pending">With Pending Credit</option>
                        <option value="all_paid">Fully Paid & Settled</option>
                    </select>
                </div>
            </div>

            {{-- Sort Options --}}
            <div class="flex items-center gap-2">
                <span class="text-[11px] font-bold text-zinc-400 uppercase tracking-wider">Sort:</span>
                <select 
                    wire:model.live="sortBy" 
                    class="text-xs rounded-xl border border-zinc-200 bg-white px-2.5 py-2 text-zinc-800 font-medium focus:border-[#1e3a8a] focus:ring-0"
                >
                    <option value="latest">Recently Added (Newest First)</option>
                    <option value="name_asc">Name (A-Z)</option>
                    <option value="name_desc">Name (Z-A)</option>
                    <option value="invoices_desc">Highest Invoices Count</option>
                    <option value="spent_desc">Highest Turnover (TZS)</option>
                    <option value="balance_desc">Highest Outstanding Debt</option>
                </select>
            </div>
        </div>

        {{-- Customers Table Container --}}
        <div class="bg-white rounded-2xl border border-zinc-200/90 shadow-xs overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs">
                    <thead class="bg-zinc-50/70 border-b border-zinc-100 text-[11px] font-semibold text-zinc-500 uppercase tracking-wider">
                        <tr>
                            <th class="py-3.5 px-4">Customer / Organization</th>
                            <th class="py-3.5 px-4">Contact & Phone</th>
                            <th class="py-3.5 px-4">Location / Address</th>
                            <th class="py-3.5 px-4 text-center">Invoice Status Breakdown</th>
                            <th class="py-3.5 px-4 text-right">Lifetime Sales</th>
                            <th class="py-3.5 px-4 text-right">Outstanding Credit</th>
                            <th class="py-3.5 px-4 text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-zinc-100">
                        @forelse ($customers as $customer)
                            <tr class="hover:bg-zinc-50/60 transition">
                                {{-- Customer Name & Type Badge --}}
                                <td class="py-3.5 px-4">
                                    <div class="flex items-center gap-2 flex-wrap">
                                        <div class="font-bold text-sm text-zinc-950">{{ $customer->name }}</div>
                                        @if($customer->tier === 'premium')
                                            <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-md text-[10px] font-black bg-amber-100 text-amber-950 border border-amber-300 shadow-2xs">
                                                <x-lucide name="crown" class="w-3.5 h-3.5 text-amber-600 fill-amber-500" />
                                                Premium
                                            </span>
                                        @elseif($customer->tier === 'medium')
                                            <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-md text-[10px] font-bold bg-blue-50 text-blue-800 border border-blue-200">
                                                <x-lucide name="award" class="w-3 h-3 text-blue-600" />
                                                Medium
                                            </span>
                                        @else
                                            <span class="inline-flex items-center px-1.5 py-0.5 rounded text-[9px] font-medium bg-zinc-100 text-zinc-600 border border-zinc-200">
                                                Standard
                                            </span>
                                        @endif
                                    </div>
                                    <div class="flex items-center gap-2 mt-1 flex-wrap">
                                        @if($customer->customer_type === 'retail')
                                            <span class="inline-flex items-center px-2 py-0.5 rounded-md text-[10px] font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                                Retail
                                            </span>
                                        @elseif($customer->customer_type === 'corporate_ngo')
                                            <span class="inline-flex items-center px-2 py-0.5 rounded-md text-[10px] font-bold bg-blue-50 text-blue-700 border border-blue-200">
                                                Corporate/NGOs
                                            </span>
                                        @elseif($customer->customer_type === 'government')
                                            <span class="inline-flex items-center px-2 py-0.5 rounded-md text-[10px] font-bold bg-purple-50 text-purple-700 border border-purple-200">
                                                Government
                                            </span>
                                        @else
                                            <span class="inline-flex items-center px-2 py-0.5 rounded-md text-[10px] font-bold bg-zinc-100 text-zinc-700 border border-zinc-200">
                                                {{ ucfirst($customer->customer_type ?? 'Retail') }}
                                            </span>
                                        @endif

                                        @if($customer->tin_number)
                                            <span class="text-[10px] font-mono text-zinc-500">TIN: {{ $customer->tin_number }}</span>
                                        @endif

                                        <span class="text-[10px] text-zinc-400 font-medium">Added: {{ $customer->created_at ? $customer->created_at->format('M d, Y, h:i A') : 'N/A' }}</span>
                                    </div>
                                </td>

                                {{-- Contact Person & Phone --}}
                                <td class="py-3.5 px-4">
                                    <div class="font-medium text-zinc-900">{{ $customer->contact_person ?: 'Direct Contact' }}</div>
                                    <div class="text-zinc-500 font-mono text-[11px] mt-0.5 flex items-center gap-1">
                                        <x-lucide name="phone" class="w-3 h-3 text-zinc-400" />
                                        {{ $customer->phone ?: 'No phone registered' }}
                                    </div>
                                    @if($customer->email)
                                        <div class="text-zinc-400 text-[10px] truncate max-w-[160px]">{{ $customer->email }}</div>
                                    @endif
                                </td>

                                {{-- Location / Address --}}
                                <td class="py-3.5 px-4 max-w-[200px]">
                                    <div class="text-zinc-600 text-xs line-clamp-2 leading-relaxed">
                                        {{ $customer->billing_address }}
                                    </div>
                                </td>

                                {{-- Invoices Status Breakdown --}}
                                <td class="py-3.5 px-4">
                                    @if($customer->computed_invoices_count > 0)
                                        <div class="flex flex-col items-center gap-1">
                                            <span class="text-[11px] font-bold text-zinc-700">
                                                {{ $customer->computed_invoices_count }} {{ Str::plural('Invoice', $customer->computed_invoices_count) }}
                                            </span>
                                            <div class="flex flex-wrap items-center justify-center gap-1">
                                                @if($customer->computed_paid_count > 0)
                                                    <span class="inline-flex items-center px-1.5 py-0.5 rounded text-[10px] font-bold bg-emerald-50 text-emerald-700 border border-emerald-200" title="{{ $customer->computed_paid_count }} Paid Invoices">
                                                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 mr-1"></span>
                                                        {{ $customer->computed_paid_count }} Paid
                                                    </span>
                                                @endif
                                                @if($customer->computed_pending_count > 0)
                                                    <span class="inline-flex items-center px-1.5 py-0.5 rounded text-[10px] font-bold bg-blue-50 text-blue-700 border border-blue-200" title="{{ $customer->computed_pending_count }} Pending Invoices">
                                                        <span class="w-1.5 h-1.5 rounded-full bg-blue-500 mr-1"></span>
                                                        {{ $customer->computed_pending_count }} Pending
                                                    </span>
                                                @endif
                                                @if($customer->computed_overdue_count > 0)
                                                    <span class="inline-flex items-center px-1.5 py-0.5 rounded text-[10px] font-bold bg-rose-50 text-rose-700 border border-rose-200" title="{{ $customer->computed_overdue_count }} Overdue Invoices">
                                                        <span class="w-1.5 h-1.5 rounded-full bg-rose-500 animate-pulse mr-1"></span>
                                                        {{ $customer->computed_overdue_count }} Overdue
                                                    </span>
                                                @endif
                                            </div>
                                        </div>
                                    @else
                                        <div class="text-center text-zinc-400 text-[11px]">No Invoices</div>
                                    @endif
                                </td>

                                {{-- Lifetime Billed --}}
                                <td class="py-3.5 px-4 text-right font-mono font-semibold text-zinc-900">
                                    TZS {{ number_format($customer->computed_spent) }}
                                    <div class="text-[10px] text-emerald-600 font-sans font-medium">
                                        Paid: TZS {{ number_format($customer->computed_paid) }}
                                    </div>
                                </td>

                                {{-- Outstanding Balance --}}
                                <td class="py-3.5 px-4 text-right">
                                    @if($customer->computed_balance > 0)
                                        <span class="font-mono font-bold text-rose-600 block">
                                            TZS {{ number_format($customer->computed_balance) }}
                                        </span>
                                        <span class="text-[10px] text-rose-500 font-semibold">Unsettled</span>
                                    @else
                                        <span class="inline-flex items-center gap-1 text-[11px] font-semibold text-emerald-600">
                                            <x-lucide name="check" class="w-3.5 h-3.5 text-emerald-500" />
                                            Settled
                                        </span>
                                    @endif
                                </td>

                                {{-- Actions --}}
                                <td class="py-3.5 px-4 text-right whitespace-nowrap">
                                    <div class="flex items-center justify-end gap-1.5">
                                        {{-- New Invoice for this customer --}}
                                        <a 
                                            href="{{ route('invoices.create') }}" 
                                            class="p-1.5 rounded-lg bg-blue-50 hover:bg-[#1e3a8a] text-[#1e3a8a] hover:text-white transition duration-150 cursor-pointer shadow-2xs"
                                            title="Create Invoice for {{ $customer->name }}"
                                        >
                                            <x-lucide name="file-plus-2" class="w-4 h-4" />
                                        </a>

                                        @if(auth()->user()?->isAdmin())
                                            {{-- Edit Customer (Pencil Icon) --}}
                                            <button 
                                                type="button" 
                                                wire:click="editCustomer({{ $customer->id }})"
                                                class="p-1.5 rounded-lg bg-zinc-100 hover:bg-zinc-200 text-zinc-700 hover:text-zinc-950 transition duration-150 cursor-pointer btn-interactive shadow-2xs"
                                                title="Edit Customer"
                                            >
                                                <x-lucide name="pencil" class="w-4 h-4" />
                                            </button>

                                            {{-- Delete Customer (Trash Icon) --}}
                                            <button 
                                                type="button" 
                                                wire:click="confirmDelete({{ $customer->id }})"
                                                class="p-1.5 rounded-lg bg-rose-50 hover:bg-rose-600 text-rose-600 hover:text-white transition duration-150 cursor-pointer btn-interactive shadow-2xs"
                                                title="Delete Customer"
                                            >
                                                <x-lucide name="trash-2" class="w-4 h-4" />
                                            </button>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="py-12 text-center text-zinc-500">
                                    <div class="max-w-xs mx-auto space-y-3">
                                        <div class="w-12 h-12 rounded-2xl bg-zinc-100 text-zinc-400 mx-auto flex items-center justify-center">
                                            <x-lucide name="users" class="w-6 h-6" />
                                        </div>
                                        <p class="font-bold text-sm text-zinc-800">No Customers Found</p>
                                        <p class="text-xs text-zinc-400">Try adjusting your search query or type filter.</p>
                                        <button 
                                            type="button" 
                                            wire:click="openCreateModal"
                                            class="inline-flex items-center px-3.5 py-1.5 text-xs font-bold text-white bg-[#0a192f] rounded-lg shadow-xs"
                                        >
                                            <x-lucide name="user-plus" class="w-3.5 h-3.5 mr-1" />
                                            Add New Customer
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </main>

    {{-- CREATE / EDIT CUSTOMER MODAL --}}
    @if($showCustomerModal)
        <div class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-zinc-950/60 backdrop-blur-xs animate-fade-in">
            <div class="bg-white w-full max-w-2xl rounded-2xl border border-zinc-200 shadow-2xl p-6 sm:p-8 space-y-5 animate-scale-up max-h-[90vh] overflow-y-auto">
                <div class="flex items-center justify-between pb-3 border-b border-zinc-100">
                    <div>
                        <h3 class="text-lg font-bold text-zinc-950">{{ $isEditing ? 'Edit Customer Information' : 'Register New Customer' }}</h3>
                        <p class="text-xs text-zinc-500 mt-0.5">Retail, Corporate/NGOs, and Government accounts.</p>
                    </div>
                    <button 
                        type="button" 
                        wire:click="closeModal" 
                        class="text-zinc-400 hover:text-zinc-700 p-1.5 rounded-lg hover:bg-zinc-100 transition cursor-pointer"
                    >
                        <x-lucide name="x" class="w-5 h-5" />
                    </button>
                </div>

                <form wire:submit.prevent="saveCustomer" novalidate class="space-y-4">
                    {{-- Customer Name --}}
                    <div>
                        <label for="modal_name" class="block text-xs font-bold text-zinc-700 mb-1">Company / Customer Name *</label>
                        <input 
                            type="text" 
                            id="modal_name" 
                            wire:model="name"
                            placeholder="e.g. Mangi Auto Garage & Logistics Ltd"
                            class="w-full text-sm rounded-xl border border-zinc-200 px-3.5 py-2.5 text-zinc-900 focus:border-[#1e3a8a] focus:ring-1 focus:ring-[#1e3a8a] transition"
                        />
                        @error('name') <span class="text-xs text-rose-500 mt-1 block">{{ $message }}</span> @enderror
                    </div>

                    {{-- Customer Type --}}
                    <div>
                        <label for="modal_type" class="block text-xs font-bold text-zinc-700 mb-1">Customer Category *</label>
                        <select 
                            id="modal_type" 
                            wire:model="customer_type"
                            class="w-full text-sm rounded-xl border border-zinc-200 px-3.5 py-2.5 text-zinc-900 bg-white focus:border-[#1e3a8a] focus:ring-1 focus:ring-[#1e3a8a] transition font-medium"
                        >
                            <option value="retail">Retail</option>
                            <option value="corporate_ngo">Corporate/NGOs</option>
                            <option value="government">Government</option>
                        </select>
                        @error('customer_type') <span class="text-xs text-rose-500 mt-1 block">{{ $message }}</span> @enderror
                    </div>

                    {{-- Contact Person, Phone & Email --}}
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                        <div>
                            <label for="modal_contact" class="block text-xs font-bold text-zinc-700 mb-1">Contact Person</label>
                            <input 
                                type="text" 
                                id="modal_contact" 
                                wire:model="contact_person"
                                placeholder="e.g. Aloyce Mangi"
                                class="w-full text-sm rounded-xl border border-zinc-200 px-3.5 py-2.5 text-zinc-900 focus:border-[#1e3a8a] focus:ring-1 focus:ring-[#1e3a8a] transition"
                            />
                        </div>

                        <div>
                            <label for="modal_phone" class="block text-xs font-bold text-zinc-700 mb-1">Phone Number</label>
                            <input 
                                type="text" 
                                id="modal_phone" 
                                wire:model="phone"
                                placeholder="e.g. +255 754 889 912"
                                class="w-full text-sm rounded-xl border border-zinc-200 px-3.5 py-2.5 text-zinc-900 font-mono focus:border-[#1e3a8a] focus:ring-1 focus:ring-[#1e3a8a] transition"
                            />
                        </div>

                        <div>
                            <label for="modal_email" class="block text-xs font-bold text-zinc-700 mb-1">Email Address</label>
                            <input 
                                type="email" 
                                id="modal_email" 
                                wire:model.live.debounce.400ms="email"
                                placeholder="mangi@garage.co.tz"
                                class="w-full text-sm rounded-xl border @error('email') border-rose-400 focus:border-rose-500 focus:ring-rose-500 @else border-zinc-200 focus:border-[#1e3a8a] focus:ring-[#1e3a8a] @enderror px-3.5 py-2.5 text-zinc-900 focus:ring-1 transition"
                            />
                            @error('email') <span class="text-xs text-rose-500 mt-1 block font-medium">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    {{-- TIN & VRN --}}
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label for="modal_tin" class="block text-xs font-bold text-zinc-700 mb-1">TRA TIN Number</label>
                            <input 
                                type="text" 
                                id="modal_tin" 
                                wire:model="tin_number"
                                placeholder="e.g. 104-892-334"
                                class="w-full text-sm rounded-xl border border-zinc-200 px-3.5 py-2.5 text-zinc-900 font-mono focus:border-[#1e3a8a] focus:ring-1 focus:ring-[#1e3a8a] transition"
                            />
                        </div>

                        <div>
                            <label for="modal_vrn" class="block text-xs font-bold text-zinc-700 mb-1">TRA VRN Number (VAT)</label>
                            <input 
                                type="text" 
                                id="modal_vrn" 
                                wire:model="vrn_number"
                                placeholder="e.g. 40-019283-K"
                                class="w-full text-sm rounded-xl border border-zinc-200 px-3.5 py-2.5 text-zinc-900 font-mono focus:border-[#1e3a8a] focus:ring-1 focus:ring-[#1e3a8a] transition"
                            />
                        </div>
                    </div>

                    {{-- Billing Address --}}
                    <div>
                        <label for="modal_address" class="block text-xs font-bold text-zinc-700 mb-1">Physical & Billing Address *</label>
                        <textarea 
                            id="modal_address" 
                            wire:model="billing_address"
                            rows="2"
                            placeholder="Plot number, Street, Ward, District, City (e.g. Plot 58, Swahili & Msimbazi Street, Kariakoo, Dar es Salaam)"
                            class="w-full text-sm rounded-xl border border-zinc-200 px-3.5 py-2.5 text-zinc-900 focus:border-[#1e3a8a] focus:ring-1 focus:ring-[#1e3a8a] transition"
                        ></textarea>
                        @error('billing_address') <span class="text-xs text-rose-500 mt-1 block">{{ $message }}</span> @enderror
                    </div>

                    {{-- Notes --}}
                    <div>
                        <label for="modal_notes" class="block text-xs font-bold text-zinc-700 mb-1">Commercial Notes & Settlement Terms</label>
                        <input 
                            type="text" 
                            id="modal_notes" 
                            wire:model="notes"
                            placeholder="e.g. Approved for 14-day credit on TBR heavy tyres"
                            class="w-full text-sm rounded-xl border border-zinc-200 px-3.5 py-2.5 text-zinc-900 focus:border-[#1e3a8a] focus:ring-1 focus:ring-[#1e3a8a] transition"
                        />
                    </div>

                    {{-- Action Buttons --}}
                    <div class="pt-4 flex items-center justify-end gap-3 border-t border-zinc-100">
                        <button 
                            type="button" 
                            wire:click="closeModal" 
                            class="px-4 py-2 text-xs font-semibold text-zinc-600 hover:text-zinc-900 rounded-xl hover:bg-zinc-100 transition cursor-pointer"
                        >
                            Cancel
                        </button>
                        <button 
                            type="submit" 
                            class="inline-flex items-center px-5 py-2 text-xs font-bold text-white bg-[#0a192f] hover:bg-[#1e3a8a] rounded-xl shadow-sm transition active:scale-95 cursor-pointer btn-interactive"
                        >
                            <x-lucide name="check" class="w-4 h-4 mr-1.5 text-emerald-400" />
                            {{ $isEditing ? 'Save Changes' : 'Register Customer' }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    @endif

    {{-- DELETE CONFIRMATION MODAL --}}
    @if($showDeleteModal)
        <div class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-zinc-950/60 backdrop-blur-xs animate-fade-in">
            <div class="bg-white w-full max-w-md rounded-2xl border border-zinc-200 shadow-2xl p-6 space-y-4 animate-scale-up">
                <div class="w-12 h-12 rounded-2xl bg-rose-50 border border-rose-200 text-rose-600 flex items-center justify-center mx-auto">
                    <x-lucide name="alert-triangle" class="w-6 h-6" />
                </div>

                <div class="text-center space-y-1">
                    <h3 class="text-base font-bold text-zinc-950">Confirm Customer Deletion</h3>
                    <p class="text-xs text-zinc-500">
                        Are you sure you want to delete <strong class="text-zinc-900">{{ $deletingCustomerName }}</strong>?
                    </p>
                    <p class="text-[11px] text-amber-700 bg-amber-50 p-2 rounded-lg border border-amber-200 mt-2">
                        Note: Existing invoices associated with this client will remain in the database with customer details intact.
                    </p>
                </div>

                <div class="flex items-center justify-end gap-3 pt-2">
                    <button 
                        type="button" 
                        wire:click="closeDeleteModal" 
                        class="px-4 py-2 text-xs font-semibold text-zinc-600 hover:text-zinc-900 rounded-xl hover:bg-zinc-100 transition cursor-pointer"
                    >
                        Cancel
                    </button>
                    <button 
                        type="button" 
                        wire:click="deleteCustomer" 
                        class="inline-flex items-center px-4 py-2 text-xs font-bold text-white bg-rose-600 hover:bg-rose-700 rounded-xl shadow-xs transition active:scale-95 cursor-pointer btn-interactive"
                    >
                        <x-lucide name="trash-2" class="w-4 h-4 mr-1.5" />
                        Yes, Delete Customer
                    </button>
                </div>
            </div>
        </div>
    @endif

    <x-page-footer />
</div>
