<div wire:poll.5s class="min-h-screen bg-[#f3f4f6] text-zinc-900 py-6 px-4 sm:px-6 lg:px-8 font-sans antialiased">
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
                    <a href="{{ route('invoices.index') }}" wire:navigate class="whitespace-nowrap px-3 py-1.5 text-xs font-semibold rounded-xl transition text-zinc-600 hover:text-zinc-900 hover:bg-zinc-100">Records</a>
                @endif
                <a href="{{ route('products.index') }}" wire:navigate class="whitespace-nowrap px-3 py-1.5 text-xs font-semibold rounded-xl transition text-zinc-600 hover:text-zinc-900 hover:bg-zinc-100">Stocks</a>
                <a href="{{ route('customers.index') }}" wire:navigate class="whitespace-nowrap px-3 py-1.5 text-xs font-semibold rounded-xl transition text-zinc-600 hover:text-zinc-900 hover:bg-zinc-100">Customers</a>
                @if(auth()->user()?->isAdmin())
                    <a href="{{ route('reports.index') }}" wire:navigate class="whitespace-nowrap px-3 py-1.5 text-xs font-semibold rounded-xl transition bg-[#0a192f] text-white shadow-xs">Reports</a>
                    <a href="{{ route('payment-methods.index') }}" wire:navigate class="whitespace-nowrap px-3 py-1.5 text-xs font-semibold rounded-xl transition text-zinc-600 hover:text-zinc-900 hover:bg-zinc-100">Payment Channels</a>
                @endif
            </nav>
        </div>

        {{-- Right: Role & Actions --}}
        <div class="flex items-center space-x-2.5 shrink-0">
            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-bold bg-amber-100 text-amber-900 border border-amber-300 whitespace-nowrap">
                Admin
            </span>

            <button 
                type="button" 
                onclick="window.print()" 
                class="inline-flex items-center whitespace-nowrap px-3.5 py-1.5 text-xs font-bold text-zinc-700 bg-white border border-zinc-200 hover:bg-zinc-50 rounded-xl shadow-xs transition cursor-pointer"
            >
                <x-lucide name="printer" class="w-3.5 h-3.5 mr-1.5 text-zinc-500" />
                Print Report
            </button>

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

    <main class="max-w-7xl mx-auto space-y-8">
        {{-- Header & Advanced Timeframe Intelligence Selector --}}
        <div class="bg-white p-5 rounded-2xl border border-zinc-200/90 shadow-xs space-y-4">
            <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-4">
                <div>
                    <h1 class="text-2xl sm:text-3xl font-extrabold tracking-tight text-zinc-950">Financial & Sales Analytics</h1>
                    <p class="text-xs text-zinc-500 mt-0.5">Filter sales, profit margins, receivables, and SKU turnover across any historical or current date window.</p>
                </div>

                {{-- Active Range Indicator Badge --}}
                <div class="inline-flex items-center gap-2 bg-blue-50/70 border border-blue-200 px-3.5 py-1.5 rounded-xl text-xs text-[#0a192f] font-semibold self-start lg:self-auto">
                    <x-lucide name="calendar" class="w-4 h-4 text-blue-600 shrink-0" />
                    <span>Period: <strong class="text-[#1e3a8a]">{{ $dateRangeDescription }}</strong></span>
                </div>
            </div>

            {{-- Period Filter Controls: Presets + Custom Date Pickers --}}
            <div class="pt-3 border-t border-zinc-100 flex flex-col md:flex-row md:items-center justify-between gap-4">
                {{-- Quick Presets --}}
                <div class="flex flex-wrap items-center gap-1.5">
                    <span class="text-[11px] font-bold text-zinc-400 uppercase tracking-wider mr-1">Timeframe:</span>
                    <button 
                        type="button" 
                        wire:click="setPeriod('today')"
                        class="px-2.5 py-1 text-xs font-semibold rounded-lg transition-all duration-200 cursor-pointer btn-interactive {{ $period === 'today' ? 'bg-[#0a192f] text-white shadow-xs' : 'bg-zinc-50 text-zinc-600 hover:bg-zinc-100 border border-zinc-200/80' }}"
                    >
                        Today
                    </button>
                    <button 
                        type="button" 
                        wire:click="setPeriod('this_week')"
                        class="px-2.5 py-1 text-xs font-semibold rounded-lg transition-all duration-200 cursor-pointer btn-interactive {{ $period === 'this_week' ? 'bg-[#0a192f] text-white shadow-xs' : 'bg-zinc-50 text-zinc-600 hover:bg-zinc-100 border border-zinc-200/80' }}"
                    >
                        This Week
                    </button>
                    <button 
                        type="button" 
                        wire:click="setPeriod('this_month')"
                        class="px-2.5 py-1 text-xs font-semibold rounded-lg transition-all duration-200 cursor-pointer btn-interactive {{ $period === 'this_month' ? 'bg-[#0a192f] text-white shadow-xs' : 'bg-zinc-50 text-zinc-600 hover:bg-zinc-100 border border-zinc-200/80' }}"
                    >
                        This Month
                    </button>
                    <button 
                        type="button" 
                        wire:click="setPeriod('last_month')"
                        class="px-2.5 py-1 text-xs font-semibold rounded-lg transition-all duration-200 cursor-pointer btn-interactive {{ $period === 'last_month' ? 'bg-[#0a192f] text-white shadow-xs' : 'bg-zinc-50 text-zinc-600 hover:bg-zinc-100 border border-zinc-200/80' }}"
                    >
                        Last Month
                    </button>
                    <button 
                        type="button" 
                        wire:click="setPeriod('last_30_days')"
                        class="px-2.5 py-1 text-xs font-semibold rounded-lg transition-all duration-200 cursor-pointer btn-interactive {{ $period === 'last_30_days' ? 'bg-[#0a192f] text-white shadow-xs' : 'bg-zinc-50 text-zinc-600 hover:bg-zinc-100 border border-zinc-200/80' }}"
                    >
                        Last 30 Days
                    </button>
                    <button 
                        type="button" 
                        wire:click="setPeriod('this_quarter')"
                        class="px-2.5 py-1 text-xs font-semibold rounded-lg transition-all duration-200 cursor-pointer btn-interactive {{ $period === 'this_quarter' ? 'bg-[#0a192f] text-white shadow-xs' : 'bg-zinc-50 text-zinc-600 hover:bg-zinc-100 border border-zinc-200/80' }}"
                    >
                        Quarter
                    </button>
                    <button 
                        type="button" 
                        wire:click="setPeriod('year_to_date')"
                        class="px-2.5 py-1 text-xs font-semibold rounded-lg transition-all duration-200 cursor-pointer btn-interactive {{ $period === 'year_to_date' ? 'bg-[#0a192f] text-white shadow-xs' : 'bg-zinc-50 text-zinc-600 hover:bg-zinc-100 border border-zinc-200/80' }}"
                    >
                        Year-to-Date (2026)
                    </button>
                    <button 
                        type="button" 
                        wire:click="setPeriod('all_time')"
                        class="px-2.5 py-1 text-xs font-semibold rounded-lg transition-all duration-200 cursor-pointer btn-interactive {{ $period === 'all_time' ? 'bg-[#0a192f] text-white shadow-xs' : 'bg-zinc-50 text-zinc-600 hover:bg-zinc-100 border border-zinc-200/80' }}"
                    >
                        All Time
                    </button>
                </div>

                {{-- Custom Date Range & Customer Filter --}}
                <div class="flex flex-wrap items-center gap-2 bg-zinc-50 p-1.5 rounded-xl border border-zinc-200">
                    <span class="text-[11px] font-bold text-zinc-500 pl-1">Filters:</span>
                    <div class="flex items-center gap-1.5">
                        <label class="text-[10px] text-zinc-400 font-semibold uppercase">Client</label>
                        <select 
                            wire:model.live="selectedCustomerId"
                            class="text-xs rounded-lg border border-zinc-200 bg-white px-2 py-1 text-zinc-800 focus:border-[#1e3a8a] focus:ring-0 font-medium cursor-pointer"
                        >
                            <option value="">All Clients</option>
                            @foreach ($allCustomersList as $cust)
                                <option value="{{ $cust->id }}">{{ $cust->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="flex items-center gap-1.5">
                        <label class="text-[10px] text-zinc-400 font-semibold uppercase">From</label>
                        <input 
                            type="date" 
                            wire:model.live="startDate"
                            class="text-xs rounded-lg border border-zinc-200 bg-white px-2 py-1 text-zinc-800 focus:border-[#1e3a8a] focus:ring-0 font-medium"
                        />
                    </div>
                    <div class="flex items-center gap-1.5">
                        <label class="text-[10px] text-zinc-400 font-semibold uppercase">To</label>
                        <input 
                            type="date" 
                            wire:model.live="endDate"
                            class="text-xs rounded-lg border border-zinc-200 bg-white px-2 py-1 text-zinc-800 focus:border-[#1e3a8a] focus:ring-0 font-medium"
                        />
                    </div>
                    @if($startDate || $endDate || $period !== 'all_time' || $selectedCustomerId)
                        <button 
                            type="button" 
                            wire:click="resetFilters" 
                            class="text-xs text-rose-600 hover:text-rose-800 px-2 py-1 rounded-lg hover:bg-rose-50 transition font-semibold cursor-pointer btn-interactive"
                            title="Reset all filters"
                        >
                            Reset
                        </button>
                    @endif
                </div>
            </div>
        </div>

        {{-- Section Navigation Pills: Quick Filter by Dimension --}}
        <div class="flex flex-wrap items-center gap-2 border-b border-zinc-200/80 pb-4">
            <button 
                type="button" 
                wire:click="setActiveTab('all')" 
                class="px-4 py-2 text-xs font-bold rounded-xl transition flex items-center gap-2 {{ $activeTab === 'all' ? 'bg-[#0a192f] text-white shadow-xs' : 'bg-white text-zinc-600 border border-zinc-200 hover:bg-zinc-50' }}"
            >
                <x-lucide name="bar-chart-3" class="w-3.5 h-3.5" />
                All Financial Visualizations
            </button>
            <button 
                type="button" 
                wire:click="setActiveTab('invoices')" 
                class="px-4 py-2 text-xs font-bold rounded-xl transition flex items-center gap-2 {{ $activeTab === 'invoices' ? 'bg-[#0a192f] text-white shadow-xs' : 'bg-white text-zinc-600 border border-zinc-200 hover:bg-zinc-50' }}"
            >
                <x-lucide name="receipt" class="w-3.5 h-3.5" />
                Invoices & Cashflow
            </button>
            <button 
                type="button" 
                wire:click="setActiveTab('customers')" 
                class="px-4 py-2 text-xs font-bold rounded-xl transition flex items-center gap-2 {{ $activeTab === 'customers' ? 'bg-[#0a192f] text-white shadow-xs' : 'bg-white text-zinc-600 border border-zinc-200 hover:bg-zinc-50' }}"
            >
                <x-lucide name="users" class="w-3.5 h-3.5" />
                Customer Accounts & Credit
            </button>
            <button 
                type="button" 
                wire:click="setActiveTab('products')" 
                class="px-4 py-2 text-xs font-bold rounded-xl transition flex items-center gap-2 {{ $activeTab === 'products' ? 'bg-[#0a192f] text-white shadow-xs' : 'bg-white text-zinc-600 border border-zinc-200 hover:bg-zinc-50' }}"
            >
                <x-lucide name="boxes" class="w-3.5 h-3.5" />
                Products & Depot Inventory
            </button>
        </div>

        {{-- 6-Grid High Level Financial KPIs --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-6 gap-4">
            {{-- Turnover --}}
            <div class="bg-white rounded-2xl p-5 border border-zinc-200/90 shadow-xs lg:col-span-2 card-interactive">
                <div class="flex items-center justify-between text-zinc-500 mb-2">
                    <span class="text-xs font-bold uppercase tracking-wider">Gross Turnover</span>
                    <div class="w-7 h-7 rounded-lg bg-blue-50 flex items-center justify-center text-[#1e3a8a]">
                        <x-lucide name="wallet" class="w-4 h-4" />
                    </div>
                </div>
                <div class="text-2xl font-black text-[#0a192f] tracking-tight">TZS {{ number_format($totalInvoiced) }}</div>
                <div class="text-[11px] text-zinc-400 mt-1 flex items-center justify-between">
                    <span>{{ $invoiceCount }} Invoices Billed</span>
                    <span class="text-emerald-600 font-semibold">+18.4% YoY</span>
                </div>
            </div>

            {{-- Cleared Cash --}}
            <div class="bg-white rounded-2xl p-5 border border-zinc-200/90 shadow-xs card-interactive">
                <div class="flex items-center justify-between text-zinc-500 mb-1.5">
                    <span class="text-[11px] font-bold uppercase tracking-wider">Cleared Cash</span>
                    <x-lucide name="check-circle-2" class="w-4 h-4 text-emerald-600" />
                </div>
                <div class="text-xl font-black text-emerald-600">TZS {{ number_format($totalPaid) }}</div>
                <div class="text-[10px] text-zinc-400 mt-1">
                    Rate: <strong class="text-emerald-700 font-bold">{{ $collectionRate }}%</strong>
                </div>
            </div>

            {{-- Outstanding Receivables --}}
            <div class="bg-white rounded-2xl p-5 border border-zinc-200/90 shadow-xs card-interactive">
                <div class="flex items-center justify-between text-zinc-500 mb-1.5">
                    <span class="text-[11px] font-bold uppercase tracking-wider">Pending Debt</span>
                    <x-lucide name="clock" class="w-4 h-4 text-amber-600" />
                </div>
                <div class="text-xl font-black text-amber-600">TZS {{ number_format($totalOutstanding) }}</div>
                <div class="text-[10px] text-zinc-400 mt-1">
                    Kariakoo Net terms
                </div>
            </div>

            {{-- TRA VAT --}}
            <div class="bg-white rounded-2xl p-5 border border-zinc-200/90 shadow-xs card-interactive">
                <div class="flex items-center justify-between text-zinc-500 mb-1.5">
                    <span class="text-[11px] font-bold uppercase tracking-wider">TRA VAT (18%)</span>
                    <x-lucide name="receipt" class="w-4 h-4 text-[#1e3a8a]" />
                </div>
                <div class="text-xl font-black text-zinc-900">TZS {{ number_format($totalVat) }}</div>
                <div class="text-[10px] text-zinc-400 mt-1">
                    Statutory remit
                </div>
            </div>

            {{-- Depot Inventory Asset Value --}}
            <div class="bg-white rounded-2xl p-5 border border-zinc-200/90 shadow-xs card-interactive">
                <div class="flex items-center justify-between text-zinc-500 mb-1.5">
                    <span class="text-[11px] font-bold uppercase tracking-wider">Stock Valuation</span>
                    <x-lucide name="disc" class="w-4 h-4 text-blue-500" />
                </div>
                <div class="text-xl font-black text-blue-900">TZS {{ number_format($totalStockValue) }}</div>
                <div class="text-[10px] text-zinc-400 mt-1">
                    {{ $totalStockCount }} tyres in store
                </div>
            </div>
        </div>

        {{-- ========================================================================= --}}
        {{-- SECTION 1: INVOICES & CASHFLOW INTELLIGENCE (Graphs 1, 2, and Aging)     --}}
        {{-- ========================================================================= --}}
        @if ($activeTab === 'all' || $activeTab === 'invoices')
            <div class="space-y-6">
                <div class="flex items-center justify-between border-b border-zinc-200 pb-3">
                    <div>
                        <h2 class="text-lg font-extrabold text-zinc-950 flex items-center gap-2">
                            <span class="w-2.5 h-2.5 rounded-full bg-[#1e3a8a]"></span>
                            Invoices, Turnover & Receivables Aging
                        </h2>
                        <p class="text-xs text-zinc-500">Revenue trajectory over time and settlement lifecycle breakdown.</p>
                    </div>
                    <span class="text-xs font-semibold text-[#1e3a8a] bg-blue-50 px-3 py-1 rounded-lg">Average Invoice: TZS {{ number_format($averageInvoiceValue) }}</span>
                </div>

                {{-- Row with Monthly Trajectory Chart (8 cols) & Invoice Status Donut (4 cols) --}}
                <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
                    
                    {{-- GRAPH 1: Monthly Revenue Trajectory (Area, Line & Bar Chart modes) --}}
                    <div class="lg:col-span-8 bg-white rounded-2xl border border-zinc-200/90 p-6 sm:p-8 shadow-xs space-y-6">
                        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                            <div>
                                <h3 class="font-bold text-base text-zinc-950 flex items-center gap-2">
                                    <span>Monthly Revenue Trajectory & Collections</span>
                                    <span class="text-[10px] uppercase tracking-wider font-extrabold px-2 py-0.5 rounded-md bg-blue-50 text-[#1e3a8a] border border-blue-200">
                                        6-Month Horizon
                                    </span>
                                </h3>
                                <p class="text-xs text-zinc-400">Total invoiced billing vs cleared cash collection across Kariakoo trading depot.</p>
                            </div>

                            {{-- Chart Type Switcher & Legend --}}
                            <div class="flex items-center gap-3">
                                <div class="hidden sm:flex items-center space-x-3 text-xs font-semibold text-zinc-500 mr-2">
                                    <div class="flex items-center gap-1.5">
                                        <span class="w-3 h-1.5 rounded-full bg-[#1e3a8a]"></span>
                                        <span class="text-[11px] text-zinc-600">Invoiced (TZS)</span>
                                    </div>
                                    <div class="flex items-center gap-1.5">
                                        <span class="w-3 h-1.5 rounded-full bg-emerald-500"></span>
                                        <span class="text-[11px] text-emerald-700">Collected (TZS)</span>
                                    </div>
                                </div>

                                <div class="flex items-center p-1 bg-zinc-100 rounded-xl">
                                    <button 
                                        type="button" 
                                        wire:click="setChartMode('area')" 
                                        class="px-2.5 py-1 rounded-lg text-xs font-bold transition cursor-pointer flex items-center gap-1 {{ $chartMode === 'area' ? 'bg-white text-zinc-900 shadow-xs' : 'text-zinc-500 hover:text-zinc-900' }}"
                                        title="Smooth Area Graph"
                                    >
                                        <x-lucide name="trending-up" class="w-3.5 h-3.5 {{ $chartMode === 'area' ? 'text-[#1e3a8a]' : '' }}" />
                                        <span>Area</span>
                                    </button>
                                    <button 
                                        type="button" 
                                        wire:click="setChartMode('line')" 
                                        class="px-2.5 py-1 rounded-lg text-xs font-bold transition cursor-pointer flex items-center gap-1 {{ $chartMode === 'line' ? 'bg-white text-zinc-900 shadow-xs' : 'text-zinc-500 hover:text-zinc-900' }}"
                                        title="Line Curve Graph"
                                    >
                                        <x-lucide name="activity" class="w-3.5 h-3.5 {{ $chartMode === 'line' ? 'text-[#1e3a8a]' : '' }}" />
                                        <span>Line</span>
                                    </button>
                                    <button 
                                        type="button" 
                                        wire:click="setChartMode('bar')" 
                                        class="px-2.5 py-1 rounded-lg text-xs font-bold transition cursor-pointer flex items-center gap-1 {{ $chartMode === 'bar' ? 'bg-white text-zinc-900 shadow-xs' : 'text-zinc-500 hover:text-zinc-900' }}"
                                        title="Compound Bar Graph"
                                    >
                                        <x-lucide name="bar-chart-3" class="w-3.5 h-3.5 {{ $chartMode === 'bar' ? 'text-[#1e3a8a]' : '' }}" />
                                        <span>Bars</span>
                                    </button>
                                </div>
                            </div>
                        </div>

                        {{-- GRAPH DISPLAY AREA --}}
                        <div class="pt-2 pb-2">
                            @if ($chartMode === 'area' || $chartMode === 'line')
                                {{-- SVG AREA & LINE GRAPH --}}
                                <div class="relative w-full h-64 select-none">
                                    <svg class="w-full h-full overflow-visible" viewBox="0 0 700 220" preserveAspectRatio="none">
                                        <defs>
                                            {{-- Revenue Area Gradient --}}
                                            <linearGradient id="revenueAreaGrad" x1="0" y1="0" x2="0" y2="1">
                                                <stop offset="0%" stop-color="#1e3a8a" stop-opacity="0.38" />
                                                <stop offset="60%" stop-color="#3b82f6" stop-opacity="0.12" />
                                                <stop offset="100%" stop-color="#3b82f6" stop-opacity="0.0" />
                                            </linearGradient>

                                            {{-- Collected Area Gradient --}}
                                            <linearGradient id="collectedAreaGrad" x1="0" y1="0" x2="0" y2="1">
                                                <stop offset="0%" stop-color="#059669" stop-opacity="0.32" />
                                                <stop offset="70%" stop-color="#10b981" stop-opacity="0.08" />
                                                <stop offset="100%" stop-color="#10b981" stop-opacity="0.0" />
                                            </linearGradient>

                                            {{-- Drop shadow glow for main line --}}
                                            <filter id="lineGlow" x="-10%" y="-10%" width="120%" height="120%">
                                                <feDropShadow dx="0" dy="3" stdDeviation="3" flood-color="#1e3a8a" flood-opacity="0.25" />
                                            </filter>
                                        </defs>

                                        {{-- Horizontal Grid Lines --}}
                                        <g class="stroke-zinc-100" stroke-width="1" stroke-dasharray="4 4">
                                            <line x1="40" y1="35" x2="660" y2="35" />
                                            <line x1="40" y1="85" x2="660" y2="85" />
                                            <line x1="40" y1="135" x2="660" y2="135" />
                                            <line x1="40" y1="185" x2="660" y2="185" stroke-dasharray="0" class="stroke-zinc-200" />
                                        </g>

                                        {{-- Y-Axis Labels --}}
                                        <g class="fill-zinc-400 text-[10px] font-mono font-medium" text-anchor="end">
                                            <text x="35" y="38">{{ $maxMonthly >= 1000000 ? round($maxMonthly / 1000000) . 'M' : number_format($maxMonthly) }}</text>
                                            <text x="35" y="88">{{ $maxMonthly >= 1000000 ? round(($maxMonthly * 0.66) / 1000000, 1) . 'M' : number_format($maxMonthly * 0.66) }}</text>
                                            <text x="35" y="138">{{ $maxMonthly >= 1000000 ? round(($maxMonthly * 0.33) / 1000000, 1) . 'M' : number_format($maxMonthly * 0.33) }}</text>
                                            <text x="35" y="188">0</text>
                                        </g>

                                        @if ($chartMode === 'area')
                                            {{-- Invoiced Revenue Area Fill --}}
                                            <path d="{{ $revenueAreaSvg }}" fill="url(#revenueAreaGrad)" class="transition-all duration-700" />

                                            {{-- Collected Cash Area Fill --}}
                                            <path d="{{ $collectedAreaSvg }}" fill="url(#collectedAreaGrad)" class="transition-all duration-700" />
                                        @endif

                                        {{-- Collected Line Curve (Emerald) --}}
                                        <path 
                                            d="{{ $collectedLineSvg }}" 
                                            fill="none" 
                                            stroke="#059669" 
                                            stroke-width="2.5" 
                                            stroke-linecap="round" 
                                            stroke-linejoin="round" 
                                            class="transition-all duration-700" 
                                        />

                                        {{-- Invoiced Line Curve (Deep Navy) --}}
                                        <path 
                                            d="{{ $revenueLineSvg }}" 
                                            fill="none" 
                                            stroke="#1e3a8a" 
                                            stroke-width="3.5" 
                                            stroke-linecap="round" 
                                            stroke-linejoin="round" 
                                            filter="url(#lineGlow)" 
                                            class="transition-all duration-700" 
                                        />

                                        {{-- Data Nodes and Labels --}}
                                        @foreach ($revenuePoints as $idx => $pt)
                                            @php
                                                $cPt = $collectedPoints[$idx];
                                            @endphp
                                            {{-- Dotted vertical guide --}}
                                            <line x1="{{ $pt['x'] }}" y1="35" x2="{{ $pt['x'] }}" y2="185" stroke="#e2e8f0" stroke-width="1" stroke-dasharray="3 3" />

                                            {{-- Collected Cash Node (Emerald) --}}
                                            <circle cx="{{ $cPt['x'] }}" cy="{{ $cPt['y'] }}" r="4" fill="#ffffff" stroke="#059669" stroke-width="2" class="hover:scale-150 transition-transform cursor-pointer" />

                                            {{-- Invoiced Revenue Node (Navy) --}}
                                            <circle cx="{{ $pt['x'] }}" cy="{{ $pt['y'] }}" r="5.5" fill="#ffffff" stroke="#1e3a8a" stroke-width="3" class="hover:scale-150 transition-transform cursor-pointer" />

                                            {{-- Month X-Axis Label --}}
                                            <text x="{{ $pt['x'] }}" y="208" fill="#64748b" font-size="11" font-weight="600" text-anchor="middle">
                                                {{ $pt['month'] }}
                                            </text>
                                        @endforeach
                                    </svg>

                                    {{-- Interactive Floating Badges on Latest Month --}}
                                    @php
                                        $latestMonthData = !empty($monthsData) ? end($monthsData) : null;
                                    @endphp
                                    @if($latestMonthData)
                                        <div class="absolute top-2 right-4 flex items-center gap-2 bg-white/90 backdrop-blur-xs border border-zinc-200/80 px-2.5 py-1.5 rounded-xl shadow-xs">
                                            <span class="w-2 h-2 rounded-full {{ ($latestMonthData['amount'] ?? 0) > 0 ? 'bg-emerald-500 animate-pulse' : 'bg-zinc-400' }}"></span>
                                            <span class="text-[11px] font-bold text-zinc-800">{{ $latestMonthData['month'] }}: TZS {{ number_format($latestMonthData['amount']) }}</span>
                                        </div>
                                    @endif
                                </div>
                            @else
                                {{-- COMPOUND BAR GRAPH --}}
                                <div class="h-64 flex items-end justify-between gap-3 sm:gap-6 border-b border-zinc-100 px-2 pb-2">
                                    @foreach ($monthsData as $item)
                                        @php
                                            $heightPercent = $maxMonthly > 0 ? round(($item['amount'] / $maxMonthly) * 85) : 10;
                                            $paidPercent = $maxMonthly > 0 ? round(($item['collected'] / $maxMonthly) * 85) : 8;
                                        @endphp
                                        <div class="flex-1 flex flex-col items-center group relative h-full justify-end">
                                            {{-- Tooltip --}}
                                            <div class="opacity-0 group-hover:opacity-100 transition duration-200 absolute -top-14 bg-[#0a192f] text-white text-[10px] py-1.5 px-2.5 rounded-lg shadow-md whitespace-nowrap z-20 pointer-events-none text-center">
                                                <span class="font-bold block">Billed: TZS {{ number_format($item['amount']) }}</span>
                                                <span class="text-emerald-400 block font-semibold">Cash: TZS {{ number_format($item['collected']) }}</span>
                                                <span class="text-blue-300 text-[9px]">{{ $item['tyres'] }} tyres • Avg TZS {{ number_format($item['aov']) }}</span>
                                            </div>

                                            {{-- Compound Bar --}}
                                            <div class="w-full max-w-[48px] flex items-end justify-center h-full gap-1">
                                                <div 
                                                    class="flex-1 bg-gradient-to-t from-[#0a192f] via-[#132743] to-[#1e3a8a] rounded-t-lg transition-all duration-300 relative group-hover:scale-105"
                                                    style="height: {{ max(10, $heightPercent) }}%;"
                                                ></div>
                                                <div 
                                                    class="flex-1 bg-gradient-to-t from-emerald-800 via-emerald-600 to-emerald-400 rounded-t-lg transition-all duration-300 relative group-hover:scale-105"
                                                    style="height: {{ max(8, $paidPercent) }}%;"
                                                ></div>
                                            </div>

                                            {{-- Month Label --}}
                                            <span class="text-[11px] font-semibold text-zinc-500 mt-2 block">{{ $item['month'] }}</span>
                                        </div>
                                    @endforeach
                                </div>
                            @endif
                        </div>

                        <div class="grid grid-cols-3 gap-4 pt-2 border-t border-zinc-100 text-center">
                            <div>
                                <span class="text-[10px] text-zinc-400 uppercase font-semibold block">Discounts Granted</span>
                                <span class="text-xs font-bold text-zinc-900">TZS {{ number_format($totalDiscounts) }}</span>
                            </div>
                            <div>
                                <span class="text-[10px] text-zinc-400 uppercase font-semibold block">Avg. Monthly Volume</span>
                                <span class="text-xs font-bold text-zinc-900">TZS {{ number_format(count($monthsData) > 0 ? round(array_sum(array_column($monthsData, 'amount')) / count($monthsData)) : 0) }}</span>
                            </div>
                            <div>
                                <span class="text-[10px] text-zinc-400 uppercase font-semibold block">Tyres Dispatched</span>
                                <span class="text-xs font-bold text-[#1e3a8a]">{{ number_format($totalTyresSold) }} Units</span>
                            </div>
                        </div>
                    </div>

                    {{-- GRAPH 2: Invoice Settlement Funnel (SVG 3-Status Donut Ring) --}}
                    <div class="lg:col-span-4 bg-white rounded-2xl border border-zinc-200/90 p-6 sm:p-8 shadow-xs flex flex-col justify-between space-y-6">
                        <div>
                            <h3 class="font-bold text-base text-zinc-950 flex items-center justify-between">
                                <span>Invoice Settlement Status</span>
                                <span class="text-[10px] font-mono px-2 py-0.5 rounded-md bg-zinc-100 text-zinc-600">{{ $invoiceCount }} Total</span>
                            </h3>
                            <p class="text-xs text-zinc-400">Distribution across Paid, Pending, and Overdue debt.</p>
                        </div>

                        {{-- SVG 3-Segment Donut Dial --}}
                        <div class="flex flex-col items-center justify-center relative py-2">
                            <svg class="w-44 h-44 transform -rotate-90" viewBox="0 0 100 100">
                                {{-- Background Track --}}
                                <circle cx="50" cy="50" r="38" stroke="#f1f5f9" stroke-width="12" fill="transparent" />
                                
                                @php
                                    $circ = 238.76;
                                    $paidPct = $invoiceStatusBreakdown[0]['percent'];
                                    $pendingPct = $invoiceStatusBreakdown[1]['percent'];
                                    $overduePct = $invoiceStatusBreakdown[2]['percent'];

                                    $dashPaid = round(($paidPct / 100) * $circ);
                                    $dashPending = round(($pendingPct / 100) * $circ);
                                    $dashOverdue = round(($overduePct / 100) * $circ);
                                @endphp

                                {{-- 1. Paid Arc (Emerald) --}}
                                @if($dashPaid > 0)
                                    <circle 
                                        cx="50" 
                                        cy="50" 
                                        r="38" 
                                        stroke="#059669" 
                                        stroke-width="12" 
                                        fill="transparent" 
                                        stroke-dasharray="{{ $dashPaid }} {{ $circ }}" 
                                        stroke-dashoffset="0"
                                        class="transition-all duration-700"
                                    />
                                @endif

                                {{-- 2. Pending Arc (Navy/Blue) --}}
                                @if($dashPending > 0)
                                    <circle 
                                        cx="50" 
                                        cy="50" 
                                        r="38" 
                                        stroke="#1e3a8a" 
                                        stroke-width="12" 
                                        fill="transparent" 
                                        stroke-dasharray="{{ $dashPending }} {{ $circ }}" 
                                        stroke-dashoffset="-{{ $dashPaid }}"
                                        class="transition-all duration-700"
                                    />
                                @endif

                                {{-- 3. Overdue Arc (Crimson Red) --}}
                                @if($dashOverdue > 0)
                                    <circle 
                                        cx="50" 
                                        cy="50" 
                                        r="38" 
                                        stroke="#dc2626" 
                                        stroke-width="12" 
                                        fill="transparent" 
                                        stroke-dasharray="{{ $dashOverdue }} {{ $circ }}" 
                                        stroke-dashoffset="-{{ $dashPaid + $dashPending }}"
                                        class="transition-all duration-700"
                                    />
                                @endif
                            </svg>

                            <div class="absolute inset-0 flex flex-col items-center justify-center text-center pointer-events-none">
                                <span class="text-2xl font-black text-[#0a192f]">{{ $collectionRate }}%</span>
                                <span class="text-[10px] text-zinc-400 font-semibold uppercase tracking-wider">Settled Rate</span>
                            </div>
                        </div>

                        {{-- Status Breakdown List --}}
                        <div class="space-y-3 pt-2 border-t border-zinc-100">
                            @foreach ($invoiceStatusBreakdown as $sb)
                                <div class="flex items-center justify-between text-xs">
                                    <div class="flex items-center gap-2">
                                        <span class="w-2.5 h-2.5 rounded-full" style="background-color: {{ $sb['color'] }};"></span>
                                        <span class="font-semibold text-zinc-700">{{ $sb['label'] }}</span>
                                    </div>
                                    <div class="text-right">
                                        <span class="font-bold text-zinc-900 block">{{ $sb['count'] }} invoices ({{ $sb['percent'] }}%)</span>
                                        <span class="text-[10px] text-zinc-500 font-mono">TZS {{ number_format($sb['amount']) }}</span>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>

                </div>

                {{-- GRAPH 3: Receivables Aging Analysis Bar --}}
                <div class="bg-white rounded-2xl border border-zinc-200/90 p-6 sm:p-8 shadow-xs space-y-4">
                    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2">
                        <div>
                            <h3 class="font-bold text-base text-zinc-950 flex items-center gap-2">
                                <span>Accounts Receivable Aging (Credit Risk Meter)</span>
                                <span class="text-[10px] font-semibold px-2 py-0.5 rounded-md bg-amber-50 text-amber-800 border border-amber-200">
                                    Maturity Lifecycle
                                </span>
                            </h3>
                            <p class="text-xs text-zinc-400">Aging breakdown of unsettled customer balances across Kariakoo trade credit terms.</p>
                        </div>
                        <div class="flex items-center gap-2">
                            <span class="text-xs font-bold text-amber-700 bg-amber-50 px-3 py-1.5 rounded-xl border border-amber-200">
                                Total Receivables: TZS {{ number_format($totalOutstanding) }}
                            </span>
                        </div>
                    </div>

                    {{-- Horizontal Stacked Distribution Bar --}}
                    @if ($totalOutstanding > 0)
                        <div class="w-full h-4 bg-zinc-100 rounded-full overflow-hidden flex shadow-inner">
                            @foreach ($agingBreakdown as $aging)
                                @if($aging['percent'] > 0)
                                    <div 
                                        style="width: {{ $aging['percent'] }}%; background-color: {{ $aging['color'] }};" 
                                        class="h-full transition-all duration-500 hover:opacity-90"
                                        title="{{ $aging['bracket'] }}: TZS {{ number_format($aging['amount']) }} ({{ $aging['percent'] }}%)"
                                    ></div>
                                @endif
                            @endforeach
                        </div>
                    @else
                        <div class="w-full py-3 bg-emerald-50 rounded-xl border border-emerald-200 text-center text-xs font-bold text-emerald-800 flex items-center justify-center gap-2">
                            <x-lucide name="check-circle-2" class="w-4 h-4 text-emerald-600" />
                            All customer invoices are fully settled! Zero outstanding receivables in this period.
                        </div>
                    @endif

                    <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 pt-3">
                        @foreach ($agingBreakdown as $aging)
                            <div class="p-3.5 rounded-xl bg-zinc-50/80 border border-zinc-100 hover:bg-zinc-100/60 transition space-y-1">
                                <div class="flex items-center gap-1.5 text-xs font-bold text-zinc-900">
                                    <span class="w-2.5 h-2.5 rounded-full" style="background-color: {{ $aging['color'] }};"></span>
                                    <span>{{ $aging['bracket'] }}</span>
                                </div>
                                <div class="text-sm font-extrabold text-zinc-950 font-mono">TZS {{ number_format($aging['amount']) }}</div>
                                <div class="flex justify-between text-[10px] text-zinc-500">
                                    <span>{{ $aging['count'] }} {{ Str::plural('Invoice', $aging['count']) }}</span>
                                    <span class="font-bold">{{ $aging['percent'] }}% of debt</span>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                {{-- FULL INVOICES TABLE WITH STATUS FILTER --}}
                <div class="bg-white rounded-2xl border border-zinc-200/90 p-6 sm:p-8 shadow-xs space-y-5">
                    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                        <div>
                            <h3 class="font-bold text-base text-zinc-950">Invoices Register (Detailed Ledger)</h3>
                            <p class="text-xs text-zinc-400">All invoices generated in the selected reporting period.</p>
                        </div>

                        {{-- Status Pill Filters --}}
                        <div class="flex items-center gap-1.5 p-1 bg-zinc-100 rounded-xl">
                            <button 
                                type="button" 
                                wire:click="setInvoiceStatusFilter('all')" 
                                class="px-3 py-1.5 rounded-lg text-xs font-bold transition cursor-pointer {{ $invoiceStatusFilter === 'all' ? 'bg-white text-zinc-900 shadow-xs' : 'text-zinc-600 hover:text-zinc-900' }}"
                            >
                                All ({{ $invoiceCount }})
                            </button>
                            <button 
                                type="button" 
                                wire:click="setInvoiceStatusFilter('paid')" 
                                class="px-3 py-1.5 rounded-lg text-xs font-bold transition cursor-pointer {{ $invoiceStatusFilter === 'paid' ? 'bg-emerald-600 text-white shadow-xs' : 'text-zinc-600 hover:text-emerald-700' }}"
                            >
                                Paid ({{ $paidCount }})
                            </button>
                            <button 
                                type="button" 
                                wire:click="setInvoiceStatusFilter('pending')" 
                                class="px-3 py-1.5 rounded-lg text-xs font-bold transition cursor-pointer {{ $invoiceStatusFilter === 'pending' ? 'bg-[#1e3a8a] text-white shadow-xs' : 'text-zinc-600 hover:text-[#1e3a8a]' }}"
                            >
                                Pending ({{ $pendingCount }})
                            </button>
                            <button 
                                type="button" 
                                wire:click="setInvoiceStatusFilter('overdue')" 
                                class="px-3 py-1.5 rounded-lg text-xs font-bold transition cursor-pointer {{ $invoiceStatusFilter === 'overdue' ? 'bg-rose-600 text-white shadow-xs' : 'text-zinc-600 hover:text-rose-700' }}"
                            >
                                Overdue ({{ $overdueCount }})
                            </button>
                        </div>
                    </div>

                    <div class="overflow-x-auto rounded-xl border border-zinc-100">
                        <table class="w-full text-left text-xs">
                            <thead class="bg-zinc-50/70 border-b border-zinc-100 text-[10px] font-semibold text-zinc-500 uppercase tracking-wider">
                                <tr>
                                    <th class="px-4 py-3.5">Invoice #</th>
                                    <th class="px-4 py-3.5">Customer</th>
                                    <th class="px-4 py-3.5">Issue Date</th>
                                    <th class="px-4 py-3.5">Due Date</th>
                                    <th class="px-4 py-3.5 text-right">Total (TZS)</th>
                                    <th class="px-4 py-3.5 text-right">Paid (TZS)</th>
                                    <th class="px-4 py-3.5 text-right">Balance Due</th>
                                    <th class="px-4 py-3.5 text-center">Status</th>
                                    <th class="px-4 py-3.5 text-right">Action</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-zinc-100">
                                @forelse ($reportInvoicesList as $inv)
                                    <tr class="hover:bg-zinc-50/60 transition">
                                        <td class="px-4 py-3.5 font-mono font-bold text-zinc-950">
                                            {{ $inv->invoice_number }}
                                        </td>
                                        <td class="px-4 py-3.5 font-medium text-zinc-900">
                                            {{ $inv->customer ? $inv->customer->name : 'Walk-in CommercialMotorist' }}
                                        </td>
                                        <td class="px-4 py-3.5 text-zinc-600">
                                            {{ $inv->issue_date ? $inv->issue_date->format('d M Y') : '-' }}
                                        </td>
                                        <td class="px-4 py-3.5 text-zinc-600">
                                            @if($inv->due_date)
                                                <span class="{{ $inv->payment_status === 'overdue' ? 'text-rose-600 font-bold' : '' }}">
                                                    {{ $inv->due_date->format('d M Y') }}
                                                </span>
                                                @if($inv->payment_status === 'overdue')
                                                    <span class="text-[10px] text-rose-500 font-semibold block">
                                                        {{ $inv->days_overdue }}d overdue
                                                    </span>
                                                @endif
                                            @else
                                                -
                                            @endif
                                        </td>
                                        <td class="px-4 py-3.5 text-right font-mono font-bold text-zinc-950">
                                            TZS {{ number_format($inv->total_amount_tzs) }}
                                        </td>
                                        <td class="px-4 py-3.5 text-right font-mono font-semibold text-emerald-600">
                                            TZS {{ number_format($inv->amount_paid_tzs) }}
                                        </td>
                                        <td class="px-4 py-3.5 text-right font-mono font-bold {{ $inv->balance_tzs > 0 ? ($inv->payment_status === 'overdue' ? 'text-rose-600' : 'text-blue-700') : 'text-zinc-400' }}">
                                            TZS {{ number_format($inv->balance_tzs) }}
                                        </td>
                                        <td class="px-4 py-3.5 text-center">
                                            @php
                                                $sBadge = match($inv->payment_status) {
                                                    'paid' => 'bg-emerald-50 text-emerald-700 border-emerald-200',
                                                    'overdue' => 'bg-rose-50 text-rose-700 border-rose-200 font-bold',
                                                    'partial' => 'bg-amber-50 text-amber-700 border-amber-200',
                                                    default => 'bg-blue-50 text-blue-800 border-blue-200',
                                                };
                                            @endphp
                                            <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[10px] font-semibold border {{ $sBadge }}">
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
                                        <td class="px-4 py-3.5 text-right space-x-1">
                                            <a href="{{ route('invoices.show', $inv) }}" class="inline-flex items-center px-2 py-0.5 text-xs font-medium text-zinc-700 bg-white border border-zinc-200 rounded-md hover:bg-zinc-50 transition">
                                                <x-lucide name="eye" class="w-3 h-3 mr-1 text-zinc-500" />
                                                View
                                            </a>
                                            <a href="{{ route('invoices.print', $inv) }}" target="_blank" class="inline-flex items-center px-2 py-0.5 text-xs font-medium text-[#1e3a8a] bg-blue-50 border border-blue-200 rounded-md hover:bg-blue-100 transition">
                                                <x-lucide name="printer" class="w-3 h-3 mr-1 text-[#1e3a8a]" />
                                                Print
                                            </a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="9" class="px-6 py-8 text-center text-zinc-400">
                                            No invoices match the selected status filter in this period.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        @endif

        {{-- ========================================================================= --}}
        {{-- SECTION 2: CUSTOMER FINANCIAL INTELLIGENCE (Graphs 4 & 5)                 --}}
        {{-- ========================================================================= --}}
        @if ($activeTab === 'all' || $activeTab === 'customers')
            <div class="space-y-6 pt-4">
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 border-b border-zinc-200 pb-3">
                    <div>
                        <h2 class="text-lg font-extrabold text-zinc-950 flex items-center gap-2">
                            <span class="w-2.5 h-2.5 rounded-full bg-blue-600"></span>
                            Customer Financial Intelligence & Credit Health
                        </h2>
                        <p class="text-xs text-zinc-500">Revenue concentration by client segment, wholesale garage performance, and receivables balance tracking.</p>
                    </div>
                    <div class="flex items-center gap-2">
                        <a 
                            href="{{ route('customers.index') }}" 
                            class="inline-flex items-center px-3 py-1.5 text-xs font-bold text-[#0a192f] bg-blue-50 border border-blue-200 hover:bg-blue-100 rounded-lg transition"
                        >
                            <x-lucide name="users" class="w-3.5 h-3.5 mr-1.5 text-blue-600" />
                            Open Customer Directory
                        </a>
                    </div>
                </div>

                {{-- Customer Analytics 4-Grid KPI Cards --}}
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                    <div class="bg-white p-4 rounded-xl border border-zinc-200/90 shadow-2xs">
                        <span class="text-[11px] font-bold text-zinc-400 uppercase tracking-wider block mb-1">Total Client Accounts</span>
                        <div class="text-xl font-black text-zinc-950">{{ $customerAnalytics['total_clients'] }} Registered</div>
                        <span class="text-[10px] text-zinc-500">{{ $customerAnalytics['retail_count'] }} Retail • {{ $customerAnalytics['corporate_ngo_count'] }} Corporate/NGOs • {{ $customerAnalytics['government_count'] }} Gov</span>
                    </div>

                    <div class="bg-white p-4 rounded-xl border border-zinc-200/90 shadow-2xs">
                        <span class="text-[11px] font-bold text-zinc-400 uppercase tracking-wider block mb-1">Active in Window</span>
                        <div class="text-xl font-black text-blue-950">{{ $customerAnalytics['active_in_period'] }} Accounts</div>
                        <span class="text-[10px] text-blue-600 font-medium">Generated invoices in selected period</span>
                    </div>

                    <div class="bg-white p-4 rounded-xl border border-zinc-200/90 shadow-2xs">
                        <span class="text-[11px] font-bold text-zinc-400 uppercase tracking-wider block mb-1">Total Credit Exposure</span>
                        <div class="text-xl font-black text-rose-950">TZS {{ number_format($customerAnalytics['total_credit_extended']) }}</div>
                        <span class="text-[10px] text-rose-600 font-medium">Pending receivable balances</span>
                    </div>

                    <div class="bg-white p-4 rounded-xl border border-zinc-200/90 shadow-2xs">
                        <span class="text-[11px] font-bold text-zinc-400 uppercase tracking-wider block mb-1">Avg Spend Per Client</span>
                        <div class="text-xl font-black text-emerald-950">TZS {{ number_format($customerAnalytics['avg_spend_per_client']) }}</div>
                        <span class="text-[10px] text-emerald-600 font-medium">Turnover per active account</span>
                    </div>
                </div>

                {{-- FULL WIDTH: Customer Segment Revenue Contribution & 6-Month Trend Line Graph --}}
                <div class="bg-white rounded-2xl border border-zinc-200/90 p-6 sm:p-8 shadow-xs space-y-6">
                    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pb-2 border-b border-zinc-100">
                        <div>
                            <h3 class="font-bold text-base text-zinc-950 flex items-center gap-2">
                                <span>Segment Revenue Contribution & Sector Growth Trends</span>
                                <span class="text-[10px] uppercase tracking-wider font-extrabold px-2 py-0.5 rounded-md bg-indigo-50 text-indigo-800 border border-indigo-200">
                                    Sector Velocity
                                </span>
                            </h3>
                            <p class="text-xs text-zinc-400">Revenue contribution and 6-month buying momentum across Corporate/NGOs, Government, and Retail clients.</p>
                        </div>

                        {{-- Line Graph Legend --}}
                        <div class="flex flex-wrap items-center gap-3 text-xs font-semibold">
                            <div class="flex items-center gap-1.5">
                                <span class="w-3 h-1.5 rounded-full bg-[#0a192f]"></span>
                                <span class="text-[11px] text-zinc-700">Corporate / NGOs</span>
                            </div>
                            <div class="flex items-center gap-1.5">
                                <span class="w-3 h-1.5 rounded-full bg-[#1e3a8a]"></span>
                                <span class="text-[11px] text-blue-800">Government</span>
                            </div>
                            <div class="flex items-center gap-1.5">
                                <span class="w-3 h-1.5 rounded-full bg-[#059669]"></span>
                                <span class="text-[11px] text-emerald-700">Retail</span>
                            </div>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-center">
                        
                        {{-- LEFT: Segment Progress Bars & Account Metrics (5 cols) --}}
                        <div class="lg:col-span-5 space-y-4">
                            <span class="text-[11px] font-bold uppercase tracking-wider text-zinc-400 block">Current Period Contribution</span>
                            @foreach ($customerSegments as $seg)
                                <div class="p-3.5 rounded-xl bg-zinc-50 border border-zinc-100 space-y-2 hover:bg-zinc-100/70 transition">
                                    <div class="flex items-center justify-between text-xs">
                                        <div class="flex items-center gap-2">
                                            <span class="w-3 h-3 rounded-md" style="background-color: {{ $seg['color'] }};"></span>
                                            <span class="font-bold text-zinc-900">{{ $seg['segment'] }}</span>
                                        </div>
                                        <span class="font-black text-sm text-[#0a192f]">{{ $seg['share'] }}%</span>
                                    </div>
                                    <div class="w-full h-2.5 bg-zinc-200 rounded-full overflow-hidden">
                                        <div 
                                            class="h-full rounded-full transition-all duration-700" 
                                            style="width: {{ $seg['share'] }}%; background-color: {{ $seg['color'] }};"
                                        ></div>
                                    </div>
                                    <div class="flex justify-between text-[11px] text-zinc-500">
                                        <span>{{ $seg['accounts'] }} Active Accounts</span>
                                        <span class="font-bold text-zinc-800">TZS {{ number_format($seg['revenue_tzs']) }}</span>
                                    </div>
                                </div>
                            @endforeach

                            <div class="bg-blue-50/70 p-3.5 rounded-xl border border-blue-100 text-xs text-zinc-600 flex items-start gap-2.5">
                                <x-lucide name="building-2" class="w-4 h-4 text-[#1e3a8a] shrink-0 mt-0.5" />
                                <div>
                                    <span class="font-bold text-[#0a192f] block">Institutional & Enterprise Partnerships</span>
                                    Corporate transport fleets, NGO programs, and Government departments account for strong bulk orders and long-term relationships.
                                </div>
                            </div>
                        </div>

                        {{-- RIGHT: 6-Month Multi-Line Trend Graph (7 cols) --}}
                        <div class="lg:col-span-7 space-y-2">
                            <div class="flex items-center justify-between">
                                <span class="text-[11px] font-bold uppercase tracking-wider text-zinc-400">6-Month Segment Revenue Trend (TZS)</span>
                                <span class="text-[10px] text-zinc-400 font-mono">{{ $segmentTrendData['months'][0] ?? '' }} — {{ end($segmentTrendData['months']) ?? '' }}</span>
                            </div>

                            <div class="relative w-full h-56 select-none bg-zinc-50/50 rounded-xl p-2 border border-zinc-100">
                                <svg class="w-full h-full overflow-visible" viewBox="0 0 700 180" preserveAspectRatio="none">
                                    <defs>
                                        {{-- Fleet Area Fill Gradient --}}
                                        <linearGradient id="fleetAreaGrad" x1="0" y1="0" x2="0" y2="1">
                                            <stop offset="0%" stop-color="#0a192f" stop-opacity="0.18" />
                                            <stop offset="100%" stop-color="#0a192f" stop-opacity="0.0" />
                                        </linearGradient>
                                    </defs>

                                    {{-- Grid Lines --}}
                                    <g class="stroke-zinc-200/60" stroke-width="1" stroke-dasharray="3 3">
                                        <line x1="45" y1="20" x2="655" y2="20" />
                                        <line x1="45" y1="65" x2="655" y2="65" />
                                        <line x1="45" y1="110" x2="655" y2="110" />
                                        <line x1="45" y1="160" x2="655" y2="160" stroke-dasharray="0" class="stroke-zinc-300" />
                                    </g>

                                    {{-- Y-Axis Values --}}
                                    <g class="fill-zinc-400 text-[9px] font-mono" text-anchor="end">
                                        <text x="38" y="24">{{ $maxSegmentVal >= 1000000 ? round($maxSegmentVal / 1000000) . 'M' : number_format($maxSegmentVal) }}</text>
                                        <text x="38" y="69">{{ $maxSegmentVal >= 1000000 ? round(($maxSegmentVal * 0.66) / 1000000, 1) . 'M' : number_format($maxSegmentVal * 0.66) }}</text>
                                        <text x="38" y="114">{{ $maxSegmentVal >= 1000000 ? round(($maxSegmentVal * 0.33) / 1000000, 1) . 'M' : number_format($maxSegmentVal * 0.33) }}</text>
                                        <text x="38" y="163">0</text>
                                    </g>

                                    {{-- Fleet Area Shading --}}
                                    <path d="{{ $fleetAreaSvg }}" fill="url(#fleetAreaGrad)" class="transition-all duration-700" />

                                    {{-- CommercialLine (Sky Blue) --}}
                                    <path d="{{ $retailLineSvg }}" fill="none" stroke="#0ea5e9" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />

                                    {{-- Garage Line (Royal Blue) --}}
                                    <path d="{{ $garageLineSvg }}" fill="none" stroke="#2563eb" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" />

                                    {{-- Fleet Line (Navy) --}}
                                    <path d="{{ $fleetLineSvg }}" fill="none" stroke="#0a192f" stroke-width="3" stroke-linecap="round" stroke-linejoin="round" />

                                    {{-- Segment Nodes and Labels --}}
                                    @foreach ($segmentTrendData['months'] as $idx => $mLabel)
                                        @php
                                            $f = $fleetPts[$idx];
                                            $g = $garagePts[$idx];
                                            $r = $retailPts[$idx];
                                        @endphp
                                        {{-- Vertical Guide --}}
                                        <line x1="{{ $f['x'] }}" y1="20" x2="{{ $f['x'] }}" y2="160" stroke="#f1f5f9" stroke-width="1" />

                                        {{-- CommercialNode --}}
                                        <circle cx="{{ $r['x'] }}" cy="{{ $r['y'] }}" r="3" fill="#ffffff" stroke="#0ea5e9" stroke-width="2" />

                                        {{-- Garage Node --}}
                                        <circle cx="{{ $g['x'] }}" cy="{{ $g['y'] }}" r="3.5" fill="#ffffff" stroke="#2563eb" stroke-width="2" />

                                        {{-- Fleet Node --}}
                                        <circle cx="{{ $f['x'] }}" cy="{{ $f['y'] }}" r="4.5" fill="#ffffff" stroke="#0a192f" stroke-width="2.5" />

                                        {{-- Month Label --}}
                                        <text x="{{ $f['x'] }}" y="174" fill="#64748b" font-size="10" font-weight="600" text-anchor="middle">
                                            {{ $mLabel }}
                                        </text>
                                    @endforeach
                                </svg>
                            </div>
                        </div>

                    </div>
                </div>

                {{-- FULL WIDTH: Customer Accounts & Invoice Status Ledger --}}
                <div class="bg-white rounded-2xl border border-zinc-200/90 shadow-xs overflow-hidden">
                    <div class="p-6 border-b border-zinc-100 flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                        <div>
                            <h3 class="font-bold text-base text-zinc-950 flex items-center gap-2">
                                <span>Customer Accounts & Invoice Status Ledger</span>
                                <span class="text-xs font-semibold px-2.5 py-0.5 rounded-md bg-zinc-100 text-zinc-700">
                                    {{ $allCustomersReport->count() }} Registered Accounts
                                </span>
                            </h3>
                            <p class="text-xs text-zinc-400">Detailed breakdown of Paid, Pending (Within Terms), and Overdue invoices attached to each customer.</p>
                        </div>
                        @if($selectedCustomerId)
                            <button 
                                type="button" 
                                wire:click="selectCustomer(null)" 
                                class="text-xs font-semibold text-[#1e3a8a] bg-blue-50 px-3 py-1.5 rounded-lg hover:bg-blue-100 transition cursor-pointer"
                            >
                                Show All Customers
                            </button>
                        @endif
                    </div>

                    <div class="overflow-x-auto">
                        <table class="w-full text-left text-xs">
                            <thead class="bg-zinc-50/70 border-b border-zinc-100 text-[10px] font-semibold text-zinc-500 uppercase tracking-wider">
                                <tr>
                                    <th class="px-6 py-3.5">Account & Contact</th>
                                    <th class="px-4 py-3.5 text-center">Paid Invoices</th>
                                    <th class="px-4 py-3.5 text-center">Pending Terms</th>
                                    <th class="px-4 py-3.5 text-center">Overdue Debt</th>
                                    <th class="px-6 py-3.5 text-right">Balance Due</th>
                                    <th class="px-4 py-3.5 text-center">Action</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-zinc-100">
                                    @forelse ($allCustomersReport as $c)
                                        <tr class="hover:bg-zinc-50/60 transition {{ $selectedCustomerId === $c['id'] ? 'bg-blue-50/50' : '' }}">
                                            <td class="px-4 py-3.5">
                                                <div class="font-bold text-zinc-950 flex items-center gap-2 flex-wrap">
                                                    <span>{{ $c['name'] }}</span>
                                                    @if(($c['tier'] ?? '') === 'premium')
                                                        <span class="inline-flex items-center gap-1 px-1.5 py-0.5 rounded text-[9px] font-black bg-amber-100 text-amber-950 border border-amber-300 shadow-2xs">
                                                            <x-lucide name="crown" class="w-3 h-3 text-amber-600 fill-amber-500" />
                                                            Premium
                                                        </span>
                                                    @elseif(($c['tier'] ?? '') === 'medium')
                                                        <span class="inline-flex items-center gap-1 px-1.5 py-0.5 rounded text-[9px] font-bold bg-blue-50 text-blue-800 border border-blue-200">
                                                            <x-lucide name="award" class="w-3 h-3 text-blue-600" />
                                                            Medium
                                                        </span>
                                                    @endif
                                                    @if($c['has_overdue'])
                                                        <span class="w-2 h-2 rounded-full bg-rose-500 animate-pulse" title="Has Overdue Invoices"></span>
                                                    @endif
                                                </div>
                                                <div class="text-[10px] text-zinc-400">
                                                    @if($c['type'] === 'retail')
                                                        <span class="text-emerald-700 font-bold">Retail</span>
                                                    @elseif($c['type'] === 'corporate_ngo')
                                                        <span class="text-blue-700 font-bold">Corporate/NGOs</span>
                                                    @elseif($c['type'] === 'government')
                                                        <span class="text-purple-700 font-bold">Government</span>
                                                    @else
                                                        <span class="capitalize">{{ str_replace('_', ' ', $c['type']) }}</span>
                                                    @endif
                                                    • {{ $c['phone'] ?: 'No phone' }}
                                                </div>
                                            </td>

                                            {{-- Paid Invoices --}}
                                            <td class="px-3 py-3.5 text-center">
                                                @if($c['paid_count'] > 0)
                                                    <span class="inline-flex items-center px-2 py-0.5 rounded-md text-[10px] font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                                        {{ $c['paid_count'] }} Paid
                                                    </span>
                                                    <div class="text-[9px] font-mono text-emerald-600 mt-0.5">
                                                        TZS {{ number_format($c['paid_amount']) }}
                                                    </div>
                                                @else
                                                    <span class="text-zinc-300 text-[10px]">-</span>
                                                @endif
                                            </td>

                                            {{-- Pending Invoices --}}
                                            <td class="px-3 py-3.5 text-center">
                                                @if($c['pending_count'] > 0)
                                                    <span class="inline-flex items-center px-2 py-0.5 rounded-md text-[10px] font-bold bg-blue-50 text-blue-700 border border-blue-200">
                                                        {{ $c['pending_count'] }} Pending
                                                    </span>
                                                    <div class="text-[9px] font-mono text-blue-600 mt-0.5">
                                                        TZS {{ number_format($c['pending_amount']) }}
                                                    </div>
                                                @else
                                                    <span class="text-zinc-300 text-[10px]">-</span>
                                                @endif
                                            </td>

                                            {{-- Overdue Invoices --}}
                                            <td class="px-3 py-3.5 text-center">
                                                @if($c['overdue_count'] > 0)
                                                    <span class="inline-flex items-center px-2 py-0.5 rounded-md text-[10px] font-bold bg-rose-50 text-rose-700 border border-rose-200">
                                                        {{ $c['overdue_count'] }} Overdue
                                                    </span>
                                                    <div class="text-[9px] font-mono text-rose-600 mt-0.5 font-bold">
                                                        TZS {{ number_format($c['overdue_amount']) }}
                                                    </div>
                                                @else
                                                    <span class="inline-flex items-center text-[10px] text-emerald-600 font-semibold">
                                                        <x-lucide name="check" class="w-3 h-3 text-emerald-500 mr-0.5" /> None
                                                    </span>
                                                @endif
                                            </td>

                                            {{-- Balance Due --}}
                                            <td class="px-4 py-3.5 text-right font-mono font-bold {{ $c['balance'] > 0 ? ($c['has_overdue'] ? 'text-rose-600' : 'text-blue-700') : 'text-zinc-400' }}">
                                                TZS {{ number_format($c['balance']) }}
                                            </td>

                                            {{-- Action Drilldown --}}
                                            <td class="px-3 py-3.5 text-center">
                                                <button 
                                                    type="button" 
                                                    wire:click="selectCustomer({{ $c['id'] }})" 
                                                    class="inline-flex items-center px-2 py-1 rounded-lg text-[11px] font-semibold transition cursor-pointer {{ $selectedCustomerId === $c['id'] ? 'bg-[#0a192f] text-white' : 'bg-zinc-100 hover:bg-zinc-200 text-zinc-700' }}"
                                                    title="View Customer's Specific Invoices"
                                                >
                                                    <x-lucide name="eye" class="w-3 h-3 mr-1 {{ $selectedCustomerId === $c['id'] ? 'text-blue-300' : 'text-zinc-500' }}" />
                                                    {{ $selectedCustomerId === $c['id'] ? 'Close' : 'Invoices' }}
                                                </button>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="6" class="px-6 py-8 text-center text-zinc-400">
                                                No customer records found in this trading period.
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>

                {{-- SPECIFIC CUSTOMER ATTACHED INVOICES DRILLDOWN (If Customer is Selected) --}}
                @if ($selectedCustomerData)
                    <div class="bg-white rounded-2xl border-2 border-[#1e3a8a]/40 p-6 sm:p-8 shadow-md space-y-5 animate-scale-up">
                        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pb-4 border-b border-zinc-100">
                            <div>
                                <div class="flex items-center gap-2">
                                    <span class="px-2 py-0.5 rounded text-[10px] font-black uppercase bg-[#0a192f] text-white">Specific Customer Invoices</span>
                                    <h3 class="font-extrabold text-lg text-zinc-950">{{ $selectedCustomerData['name'] }}</h3>
                                </div>
                                <p class="text-xs text-zinc-500 mt-1">
                                    Tel: {{ $selectedCustomerData['phone'] ?: 'N/A' }} • Type: {{ ucwords(str_replace('_', ' ', $selectedCustomerData['type'])) }} • {{ $selectedCustomerData['invoices_count'] }} Total Invoices
                                </p>
                            </div>

                            {{-- Summary Badges for this Specific Customer --}}
                            <div class="flex flex-wrap items-center gap-2">
                                <div class="px-3 py-1.5 rounded-xl bg-emerald-50 border border-emerald-200 text-right">
                                    <span class="text-[10px] text-emerald-600 font-bold uppercase block">Paid Invoices</span>
                                    <span class="text-xs font-mono font-bold text-emerald-700">{{ $selectedCustomerData['paid_count'] }} (TZS {{ number_format($selectedCustomerData['paid_amount']) }})</span>
                                </div>
                                <div class="px-3 py-1.5 rounded-xl bg-blue-50 border border-blue-200 text-right">
                                    <span class="text-[10px] text-blue-600 font-bold uppercase block">Pending Terms</span>
                                    <span class="text-xs font-mono font-bold text-blue-800">{{ $selectedCustomerData['pending_count'] }} (TZS {{ number_format($selectedCustomerData['pending_amount']) }})</span>
                                </div>
                                <div class="px-3 py-1.5 rounded-xl bg-rose-50 border border-rose-200 text-right">
                                    <span class="text-[10px] text-rose-600 font-bold uppercase block">Overdue Invoices</span>
                                    <span class="text-xs font-mono font-bold text-rose-700">{{ $selectedCustomerData['overdue_count'] }} (TZS {{ number_format($selectedCustomerData['overdue_amount']) }})</span>
                                </div>
                                <button 
                                    type="button" 
                                    wire:click="selectCustomer(null)" 
                                    class="p-2 rounded-xl bg-zinc-100 hover:bg-zinc-200 text-zinc-500 transition cursor-pointer"
                                    title="Close Customer Drilldown"
                                >
                                    <x-lucide name="x" class="w-4 h-4" />
                                </button>
                            </div>
                        </div>

                        {{-- Invoices Table for this specific customer --}}
                        <div class="overflow-x-auto rounded-xl border border-zinc-200/80">
                            <table class="w-full text-left text-xs">
                                <thead class="bg-zinc-50 text-[10px] font-semibold text-zinc-500 uppercase tracking-wider">
                                    <tr>
                                        <th class="px-4 py-3">Invoice #</th>
                                        <th class="px-4 py-3">Issue Date</th>
                                        <th class="px-4 py-3">Due Date</th>
                                        <th class="px-4 py-3 text-right">Total Amount</th>
                                        <th class="px-4 py-3 text-right">Amount Paid</th>
                                        <th class="px-4 py-3 text-right">Balance Due</th>
                                        <th class="px-4 py-3 text-center">Status</th>
                                        <th class="px-4 py-3 text-right">Action</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-zinc-100">
                                    @forelse ($selectedCustomerData['invoices'] as $inv)
                                        <tr class="hover:bg-zinc-50/70 transition">
                                            <td class="px-4 py-3 font-mono font-bold text-zinc-950">
                                                {{ $inv->invoice_number }}
                                            </td>
                                            <td class="px-4 py-3 text-zinc-600">
                                                {{ $inv->issue_date ? $inv->issue_date->format('d M Y') : '-' }}
                                            </td>
                                            <td class="px-4 py-3 text-zinc-600">
                                                @if($inv->due_date)
                                                    <span class="{{ $inv->payment_status === 'overdue' ? 'text-rose-600 font-bold' : '' }}">
                                                        {{ $inv->due_date->format('d M Y') }}
                                                    </span>
                                                    @if($inv->payment_status === 'overdue')
                                                        <span class="text-[10px] text-rose-500 font-semibold block">
                                                            {{ $inv->days_overdue }} days overdue
                                                        </span>
                                                    @endif
                                                @else
                                                    -
                                                @endif
                                            </td>
                                            <td class="px-4 py-3 text-right font-mono font-bold text-zinc-950">
                                                TZS {{ number_format($inv->total_amount_tzs) }}
                                            </td>
                                            <td class="px-4 py-3 text-right font-mono text-emerald-600 font-semibold">
                                                TZS {{ number_format($inv->amount_paid_tzs) }}
                                            </td>
                                            <td class="px-4 py-3 text-right font-mono font-bold {{ $inv->balance_tzs > 0 ? 'text-rose-600' : 'text-zinc-400' }}">
                                                TZS {{ number_format($inv->balance_tzs) }}
                                            </td>
                                            <td class="px-4 py-3 text-center">
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
                                            <td class="px-4 py-3 text-right space-x-1">
                                                <a href="{{ route('invoices.show', $inv) }}" class="inline-flex items-center px-2 py-0.5 text-xs font-medium text-zinc-700 bg-white border border-zinc-200 rounded-md hover:bg-zinc-50 transition">
                                                    <x-lucide name="eye" class="w-3 h-3 mr-1 text-zinc-500" />
                                                    View
                                                </a>
                                                <a href="{{ route('invoices.print', $inv) }}" target="_blank" class="inline-flex items-center px-2 py-0.5 text-xs font-medium text-[#1e3a8a] bg-blue-50 border border-blue-200 rounded-md hover:bg-blue-100 transition">
                                                    <x-lucide name="printer" class="w-3 h-3 mr-1 text-[#1e3a8a]" />
                                                    Print
                                                </a>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="8" class="px-6 py-6 text-center text-zinc-400">
                                                No invoices recorded for this customer in the selected time period.
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                @endif
            </div>
        @endif

        {{-- ========================================================================= --}}
        {{-- SECTION 3: PRODUCTS & DEPOT INVENTORY ECONOMICS (In-Stocks vs Out-Stocks) --}}
        {{-- ========================================================================= --}}
        @if ($activeTab === 'all' || $activeTab === 'products')
            <div class="space-y-6 pt-4">
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2 border-b border-zinc-200 pb-3">
                    <div>
                        <h2 class="text-lg font-extrabold text-zinc-950 flex items-center gap-2">
                            <span class="w-2.5 h-2.5 rounded-full bg-emerald-600"></span>
                            Inventory Flow: In-Stocks (Received) vs Out-Stocks (Sold)
                        </h2>
                        <p class="text-xs text-zinc-500">Real-time stock balance between received tyre batches in warehouse and units sold across invoices.</p>
                    </div>
                    <span class="text-xs font-semibold text-emerald-700 bg-emerald-50 px-3 py-1 rounded-lg">Depot Floor Valuation: TZS {{ number_format($inStocksValuation) }}</span>
                </div>

                {{-- 2 HERO METRIC CARDS: IN-STOCKS vs OUT-STOCKS --}}
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    {{-- 1. IN-STOCKS CARD --}}
                    <div class="bg-white rounded-2xl border border-emerald-200 p-6 sm:p-8 shadow-xs relative overflow-hidden flex flex-col justify-between">
                        <div class="absolute -right-6 -bottom-6 w-32 h-32 bg-emerald-50 rounded-full opacity-60 pointer-events-none"></div>
                        <div>
                            <div class="flex items-center justify-between mb-3">
                                <div class="flex items-center gap-2.5">
                                    <div class="w-10 h-10 rounded-xl bg-emerald-500 text-white flex items-center justify-center shadow-xs">
                                        <x-lucide name="package-check" class="w-5 h-5" />
                                    </div>
                                    <div>
                                        <span class="text-xs font-black uppercase tracking-wider text-emerald-800">In-Stocks (Received & Available)</span>
                                        <span class="text-[11px] text-zinc-400 block">Current stock on Kariakoo depot floor</span>
                                    </div>
                                </div>
                                <span class="px-2.5 py-1 rounded-full text-[10px] font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                    Depot Stock
                                </span>
                            </div>

                            <div class="mt-4">
                                <div class="text-3xl sm:text-4xl font-black text-zinc-950 tracking-tight">
                                    {{ number_format($currentInStocksCount) }} <span class="text-lg font-bold text-emerald-600">Tyres</span>
                                </div>
                                <div class="text-xs text-zinc-500 mt-1 flex items-center gap-1.5 font-medium">
                                    <span>Stock Asset Value:</span>
                                    <strong class="text-zinc-900 font-mono text-sm">TZS {{ number_format($inStocksValuation) }}</strong>
                                </div>
                            </div>
                        </div>

                        <div class="mt-6 pt-4 border-t border-emerald-100 flex items-center justify-between text-xs">
                            <span class="text-zinc-500">Total Stocks Handled / Received:</span>
                            <span class="font-bold text-emerald-800 font-mono">{{ number_format($totalStocksReceivedCumulative) }} units</span>
                        </div>
                    </div>

                    {{-- 2. OUT-STOCKS CARD --}}
                    <div class="bg-white rounded-2xl border border-blue-200 p-6 sm:p-8 shadow-xs relative overflow-hidden flex flex-col justify-between">
                        <div class="absolute -right-6 -bottom-6 w-32 h-32 bg-blue-50 rounded-full opacity-60 pointer-events-none"></div>
                        <div>
                            <div class="flex items-center justify-between mb-3">
                                <div class="flex items-center gap-2.5">
                                    <div class="w-10 h-10 rounded-xl bg-[#0a192f] text-white flex items-center justify-center shadow-xs">
                                        <x-lucide name="truck" class="w-5 h-5" />
                                    </div>
                                    <div>
                                        <span class="text-xs font-black uppercase tracking-wider text-[#0a192f]">Out-Stocks (Dispatched & Sold)</span>
                                        <span class="text-[11px] text-zinc-400 block">Tyre units invoiced and dispatched to customers</span>
                                    </div>
                                </div>
                                <span class="px-2.5 py-1 rounded-full text-[10px] font-bold bg-blue-50 text-blue-800 border border-blue-200">
                                    Sales Volume
                                </span>
                            </div>

                            <div class="mt-4">
                                <div class="text-3xl sm:text-4xl font-black text-zinc-950 tracking-tight">
                                    {{ number_format($periodOutStocksCount) }} <span class="text-lg font-bold text-[#1e3a8a]">Tyres Sold</span>
                                </div>
                                <div class="text-xs text-zinc-500 mt-1 flex items-center gap-1.5 font-medium">
                                    <span>Sales Revenue Generated:</span>
                                    <strong class="text-zinc-900 font-mono text-sm">TZS {{ number_format($periodOutStocksRevenue) }}</strong>
                                </div>
                            </div>
                        </div>

                        <div class="mt-6 pt-4 border-t border-blue-100 flex items-center justify-between text-xs">
                            <span class="text-zinc-500">All-Time Cumulative Out-Stocks:</span>
                            <span class="font-bold text-[#0a192f] font-mono">{{ number_format($allTimeOutStocksCount) }} units dispatched</span>
                        </div>
                    </div>
                </div>

                {{-- INVENTORY FLOW & RATIO METER --}}
                @php
                    $totalFlow = $currentInStocksCount + $periodOutStocksCount;
                    $inPct = $totalFlow > 0 ? round(($currentInStocksCount / $totalFlow) * 100) : 50;
                    $outPct = max(0, 100 - $inPct);
                @endphp
                <div class="bg-white rounded-2xl border border-zinc-200/90 p-5 shadow-xs space-y-3">
                    <div class="flex flex-col sm:flex-row sm:items-center justify-between text-xs gap-2">
                        <div class="flex items-center gap-4">
                            <div class="flex items-center gap-1.5">
                                <span class="w-3 h-3 rounded-full bg-emerald-500"></span>
                                <span class="font-bold text-zinc-800">In-Stocks on Floor: {{ $inPct }}% ({{ number_format($currentInStocksCount) }} tyres)</span>
                            </div>
                            <div class="flex items-center gap-1.5">
                                <span class="w-3 h-3 rounded-full bg-[#0a192f]"></span>
                                <span class="font-bold text-zinc-800">Out-Stocks Sold: {{ $outPct }}% ({{ number_format($periodOutStocksCount) }} tyres)</span>
                            </div>
                        </div>
                        <span class="text-[11px] text-zinc-400 font-medium">Inventory Turnover Clearance</span>
                    </div>

                    <div class="w-full h-3.5 bg-zinc-100 rounded-full overflow-hidden flex shadow-inner">
                        <div class="h-full bg-emerald-500 transition-all duration-700" style="width: {{ $inPct }}%;" title="In-Stocks: {{ $inPct }}%"></div>
                        <div class="h-full bg-[#0a192f] transition-all duration-700" style="width: {{ $outPct }}%;" title="Out-Stocks: {{ $outPct }}%"></div>
                    </div>
                </div>

                {{-- DETAILED IN-STOCK & OUT-STOCK SKU MOVEMENT TABLE --}}
                <div class="bg-white rounded-2xl border border-zinc-200/90 shadow-xs overflow-hidden">
                    <div class="p-6 border-b border-zinc-100 flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                        <div>
                            <h3 class="font-bold text-base text-zinc-950 flex items-center gap-2">
                                <span>Depot Stock Movement by Profile (In-Stocks vs Out-Stocks)</span>
                                <span class="text-xs font-semibold px-2.5 py-0.5 rounded-md bg-zinc-100 text-zinc-700">
                                    {{ $inventoryMovementList->count() }} Profiles
                                </span>
                            </h3>
                            <p class="text-xs text-zinc-400">Total received units, remaining in-stocks in depot, units sold (out-stocks), and clearance rates.</p>
                        </div>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="w-full text-left text-xs">
                            <thead class="bg-zinc-50/70 border-b border-zinc-100 text-[10px] font-semibold text-zinc-500 uppercase tracking-wider">
                                <tr>
                                    <th class="px-6 py-3.5">Tyre Model & SKU</th>
                                    <th class="px-4 py-3.5">Size Profile</th>
                                    <th class="px-4 py-3.5">Category</th>
                                    <th class="px-4 py-3.5 text-center">Total Received</th>
                                    <th class="px-4 py-3.5 text-center">In-Stocks (Depot)</th>
                                    <th class="px-4 py-3.5 text-center">Out-Stocks (Sold)</th>
                                    <th class="px-4 py-3.5 text-center">Turnover Rate</th>
                                    <th class="px-6 py-3.5 text-right">In-Stock Value</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-zinc-100">
                                @forelse ($inventoryMovementList as $item)
                                    <tr class="hover:bg-zinc-50/60 transition">
                                        <td class="px-6 py-3.5 flex items-center space-x-3">
                                            <img class="w-9 h-9 rounded-lg object-cover ring-1 ring-zinc-200" src="{{ $item['image_url'] }}" alt="{{ $item['brand'] }}">
                                            <div>
                                                <span class="font-bold text-zinc-950 block">{{ $item['brand'] }}</span>
                                                <span class="text-[11px] text-zinc-500">{{ $item['pattern'] }}</span>
                                                <span class="text-[9px] font-mono text-zinc-400 block uppercase">{{ $item['sku'] }}</span>
                                            </div>
                                        </td>
                                        <td class="px-4 py-3.5 font-bold text-[#1e3a8a]">
                                            {{ $item['size'] }}
                                        </td>
                                        <td class="px-4 py-3.5 text-zinc-600 font-medium">
                                            {{ $item['category'] }}
                                        </td>
                                        <td class="px-4 py-3.5 text-center font-mono font-bold text-zinc-700">
                                            {{ $item['total_received_qty'] }} pcs
                                        </td>
                                        <td class="px-4 py-3.5 text-center">
                                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-bold {{ $item['is_low_stock'] ? 'bg-rose-50 text-rose-700 border border-rose-200' : 'bg-emerald-50 text-emerald-700 border border-emerald-200' }}">
                                                {{ $item['in_stock_qty'] }} pcs
                                            </span>
                                        </td>
                                        <td class="px-4 py-3.5 text-center">
                                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-bold bg-blue-50 text-blue-800 border border-blue-200">
                                                {{ $item['out_stock_qty'] }} pcs
                                            </span>
                                        </td>
                                        <td class="px-4 py-3.5 text-center">
                                            <div class="flex items-center justify-center gap-1.5">
                                                <div class="w-12 h-1.5 bg-zinc-100 rounded-full overflow-hidden">
                                                    <div class="h-full bg-[#1e3a8a] rounded-full" style="width: {{ $item['turnover_pct'] }}%;"></div>
                                                </div>
                                                <span class="font-bold font-mono text-[11px] text-zinc-800">{{ $item['turnover_pct'] }}%</span>
                                            </div>
                                        </td>
                                        <td class="px-6 py-3.5 text-right font-mono font-bold text-zinc-950">
                                            TZS {{ number_format($item['in_stock_valuation']) }}
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="8" class="px-6 py-6 text-center text-zinc-400">
                                            No product stock movements recorded.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                {{-- BRAND, RIM, SETTLEMENT GRAPHS GRID --}}
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                    {{-- GRAPH 6: Tyre Brand Revenue & Gross Margin Economics --}}
                    <div class="bg-white rounded-2xl border border-zinc-200/90 p-6 sm:p-8 shadow-xs space-y-6">
                        <div>
                            <h3 class="font-bold text-base text-zinc-950">Brand Sales & Margin Mix</h3>
                            <p class="text-xs text-zinc-400">Share of depot sales by tyre manufacturer brand.</p>
                        </div>

                        <div class="space-y-4">
                            @foreach ($brandEconomics as $brand)
                                <div class="space-y-1.5">
                                    <div class="flex items-center justify-between text-xs">
                                        <span class="font-bold text-zinc-900">{{ $brand['brand'] }}</span>
                                        <div class="flex items-center gap-2">
                                            <span class="text-[10px] font-semibold text-emerald-600 bg-emerald-50 px-1.5 py-0.5 rounded">Margin {{ $brand['margin'] }}</span>
                                            <span class="font-extrabold text-[#0a192f]">{{ $brand['share'] }}%</span>
                                        </div>
                                    </div>
                                    <div class="w-full h-2.5 bg-zinc-100 rounded-full overflow-hidden">
                                        <div 
                                            class="h-full rounded-full transition-all duration-500" 
                                            style="width: {{ $brand['share'] }}%; background-color: {{ $brand['color'] }};"
                                        ></div>
                                    </div>
                                    <div class="flex justify-between text-[11px] text-zinc-400">
                                        <span>{{ $brand['units'] }} Tyres Dispatched</span>
                                        <span class="font-semibold text-zinc-700">TZS {{ number_format($brand['revenue']) }}</span>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    {{-- GRAPH 7: Rim Size & Vehicle Profile Matrix --}}
                    <div class="bg-white rounded-2xl border border-zinc-200/90 p-6 sm:p-8 shadow-xs space-y-6">
                        <div>
                            <h3 class="font-bold text-base text-zinc-950">Rim Size & Profile Share</h3>
                            <p class="text-xs text-zinc-400">Volume proportion across vehicle wheel dimensions.</p>
                        </div>

                        <div class="space-y-5">
                            @foreach ($rimDistribution as $rim)
                                <div class="space-y-1.5">
                                    <div class="flex items-center justify-between text-xs">
                                        <span class="font-semibold text-zinc-800">{{ $rim['size'] }}</span>
                                        <span class="font-extrabold text-[#0a192f]">{{ $rim['share'] }}%</span>
                                    </div>
                                    <div class="w-full h-2.5 bg-zinc-100 rounded-full overflow-hidden">
                                        <div 
                                            class="h-full rounded-full transition-all duration-500" 
                                            style="width: {{ $rim['share'] }}%; background-color: {{ $rim['color'] }};"
                                        ></div>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <div class="p-4 rounded-xl bg-zinc-50 border border-zinc-100 space-y-1 text-xs text-zinc-600">
                            <span class="font-bold text-zinc-900 block">Tyre Profile Distribution</span>
                            Volume proportion across wheel dimensions dynamically tracked from Kariakoo depot inventory and sales records.
                        </div>
                    </div>

                    {{-- GRAPH 8: Settlement Channel Breakdown --}}
                    <div class="bg-white rounded-2xl border border-zinc-200/90 p-6 sm:p-8 shadow-xs space-y-6">
                        <div>
                            <h3 class="font-bold text-base text-zinc-950">Payments Mix</h3>
                            <p class="text-xs text-zinc-400">Settled receipts across banking and mobile money channels.</p>
                        </div>

                        <div class="space-y-4">
                            @foreach ($settlementChannels as $ch)
                                <div class="space-y-1.5">
                                    <div class="flex items-center justify-between text-xs">
                                        <div class="flex items-center gap-1.5">
                                            <span class="w-2.5 h-2.5 rounded-full" style="background-color: {{ $ch['color'] }};"></span>
                                            <span class="font-bold text-zinc-800">{{ $ch['channel'] }}</span>
                                        </div>
                                        <span class="font-extrabold text-[#0a192f]">{{ $ch['share'] }}%</span>
                                    </div>
                                    <div class="w-full h-2.5 bg-zinc-100 rounded-full overflow-hidden">
                                        <div 
                                            class="h-full rounded-full" 
                                            style="width: {{ $ch['share'] }}%; background-color: {{ $ch['color'] }};"
                                        ></div>
                                    </div>
                                    <div class="flex justify-end text-[11px] text-zinc-400">
                                        <span class="font-semibold text-zinc-700">TZS {{ number_format($ch['amount']) }}</span>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <div class="p-4 rounded-xl bg-emerald-50 border border-emerald-100 text-xs text-emerald-900">
                            <span class="font-bold block mb-0.5">Payment Channels</span>
                            Direct bank transfers and mobile money channels configured in settings for client settlement.
                        </div>
                    </div>
                </div>

            </div>
        @endif

    </main>

    <x-page-footer />
</div>
