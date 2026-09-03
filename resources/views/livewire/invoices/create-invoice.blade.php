<div wire:poll.5s class="min-h-screen bg-[#f3f4f6] text-zinc-900 py-6 px-4 sm:px-6 lg:px-8 font-sans antialiased">
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
                <a href="{{ route('invoices.create') }}" wire:navigate class="whitespace-nowrap px-3 py-1.5 text-xs font-semibold rounded-xl transition bg-[#0a192f] text-white shadow-xs">Invoice</a>
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

    {{-- Main Container --}}
    <main class="max-w-7xl mx-auto">
        {{-- Flash / Status message --}}
        @if (session()->has('success'))
            <div class="mb-6 p-4 rounded-xl bg-blue-50 border border-blue-200 text-blue-900 flex items-center gap-3">
                <x-lucide name="check-circle-2" class="w-5 h-5 text-blue-600 shrink-0" />
                <span class="text-sm font-medium">{{ session('success') }}</span>
            </div>
        @endif

        {{-- Sub-header with Back button & Action buttons --}}
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
            <div>
                @if(auth()->user()?->isAdmin())
                    <a href="{{ route('invoices.index') }}" class="inline-flex items-center text-xs font-medium text-zinc-500 hover:text-zinc-800 transition mb-1">
                        <x-lucide name="arrow-left" class="w-3.5 h-3.5 mr-1" />
                        Back to records
                    </a>
                @else
                    <a href="{{ route('products.index') }}" class="inline-flex items-center text-xs font-medium text-zinc-500 hover:text-zinc-800 transition mb-1">
                        <x-lucide name="arrow-left" class="w-3.5 h-3.5 mr-1" />
                        Back to stocks
                    </a>
                @endif
                <h1 class="text-2xl sm:text-3xl font-extrabold tracking-tight text-zinc-950">Create New Invoice</h1>
            </div>

            <div class="flex items-center space-x-3">
                <button 
                    type="button" 
                    wire:click="downloadPdf" 
                    wire:loading.attr="disabled"
                    class="inline-flex items-center px-4 py-2 text-sm font-medium text-zinc-700 bg-white border border-zinc-300 rounded-full hover:bg-zinc-50 shadow-xs transition active:scale-95 cursor-pointer btn-interactive"
                >
                    <x-lucide name="download" class="w-4 h-4 mr-2 text-blue-600" />
                    Download PDF
                </button>

                <button 
                    type="button" 
                    wire:click="saveInvoice('issued')" 
                    wire:loading.attr="disabled"
                    class="inline-flex items-center px-5 py-2 text-sm font-semibold text-white bg-[#0a192f] hover:bg-[#1e3a8a] rounded-full shadow-sm transition active:scale-95 cursor-pointer"
                >
                    <x-lucide name="check-circle" class="w-4 h-4 mr-2 text-blue-400" />
                    Issue Invoice
                </button>
            </div>
        </div>

        {{-- Two Columns: Form on Left (7 cols), Live Preview on Right (5 cols) --}}
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
            
            {{-- LEFT COLUMN: Invoice Details Form --}}
            <div class="lg:col-span-7 bg-white rounded-2xl p-6 sm:p-8 border border-zinc-200/90 shadow-xs space-y-6">
                <h2 class="text-base font-bold text-zinc-900 tracking-tight">Invoice Details</h2>

                {{-- Customer Section --}}
                <div class="space-y-4">
                    <div>
                        <div class="flex items-center justify-between mb-1.5">
                            <label for="customer_name" class="block text-xs font-semibold text-zinc-700">Customer *</label>
                            @if(count($customers) > 0)
                                <div class="text-[11px] text-zinc-500 flex items-center gap-1.5">
                                    <span>Quick Pick:</span>
                                    <select wire:model.live="customer_id" class="text-[11px] font-medium text-blue-900 bg-blue-50/70 border border-blue-200 rounded px-1.5 py-0.5 focus:outline-hidden">
                                        <option value="">-- Choose Kariakoo Garage / Client --</option>
                                        @foreach($customers as $c)
                                            <option value="{{ $c->id }}">
                                                {{ $c->name }} ({{ $c->customer_type }}) — {{ $c->tier_label }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            @endif
                        </div>
                        <input 
                            type="text" 
                            id="customer_name" 
                            wire:model.live.debounce.300ms="customer_name" 
                            placeholder="Customer or Company Name (e.g. Mangi Auto Care, PT Nusantara Digital)"
                            class="w-full text-sm rounded-xl border border-zinc-200 px-3.5 py-2.5 text-zinc-900 placeholder:text-zinc-400 focus:border-[#1e3a8a] focus:ring-1 focus:ring-[#1e3a8a] transition"
                        />
                        @error('customer_name') <span class="text-xs text-rose-500 mt-1 block">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label for="billing_address" class="block text-xs font-semibold text-zinc-700 mb-1.5">Billing Address *</label>
                        <textarea 
                            id="billing_address" 
                            wire:model.live.debounce.300ms="billing_address" 
                            rows="2"
                            placeholder="Plot / Street / City, TIN Number, Phone"
                            class="w-full text-sm rounded-xl border border-zinc-200 px-3.5 py-2.5 text-zinc-900 placeholder:text-zinc-400 focus:border-[#1e3a8a] focus:ring-1 focus:ring-[#1e3a8a] transition"
                        ></textarea>
                        @error('billing_address') <span class="text-xs text-rose-500 mt-1 block">{{ $message }}</span> @enderror
                    </div>
                </div>

                {{-- Dates and Payment Terms Grid --}}
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                    <div>
                        <label for="issue_date" class="block text-xs font-semibold text-zinc-700 mb-1.5">Issue Date *</label>
                        <div class="relative">
                            <input 
                                type="date" 
                                id="issue_date" 
                                wire:model.live="issue_date"
                                class="w-full text-sm rounded-xl border border-zinc-200 pl-3.5 pr-9 py-2.5 text-zinc-900 focus:border-[#1e3a8a] focus:ring-1 focus:ring-[#1e3a8a] transition"
                            />
                            <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none text-zinc-400">
                                <x-lucide name="calendar" class="w-4 h-4" />
                            </div>
                        </div>
                    </div>

                    <div>
                        <label for="due_date" class="block text-xs font-semibold text-zinc-700 mb-1.5">Due Date *</label>
                        <div class="relative">
                            <input 
                                type="date" 
                                id="due_date" 
                                wire:model.live="due_date"
                                class="w-full text-sm rounded-xl border border-zinc-200 pl-3.5 pr-9 py-2.5 text-zinc-900 focus:border-[#1e3a8a] focus:ring-1 focus:ring-[#1e3a8a] transition"
                            />
                            <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none text-zinc-400">
                                <x-lucide name="calendar" class="w-4 h-4" />
                            </div>
                        </div>
                    </div>

                    <div>
                        <label for="payment_terms" class="block text-xs font-semibold text-zinc-700 mb-1.5">Payment Terms *</label>
                        <div class="relative">
                            <select 
                                id="payment_terms" 
                                wire:model.live="payment_terms"
                                class="w-full text-sm rounded-xl border border-zinc-200 pl-3.5 pr-8 py-2.5 text-zinc-900 bg-white focus:border-[#1e3a8a] focus:ring-1 focus:ring-[#1e3a8a] transition appearance-none font-medium"
                            >
                                <option value="3 Days">3 Days</option>
                                <option value="7 Days">7 Days</option>
                                <option value="14 Days">14 Days</option>
                                <option value="30 Days">30 Days</option>
                                <option value="Cash on Delivery">Cash on Delivery</option>
                                <option value="Counter Cash">Counter Cash</option>
                                <option value="50% Advance, 50% on Delivery">50% Advance, 50% on Delivery</option>
                            </select>
                            <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none text-zinc-400">
                                <x-lucide name="chevron-down" class="w-4 h-4" />
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Billed By / Issue Person Details --}}
                <div class="p-4 bg-zinc-50/80 rounded-xl border border-zinc-200/80 space-y-3">
                    <div class="flex items-center justify-between">
                        <span class="text-xs font-bold text-zinc-800 uppercase tracking-wider flex items-center gap-1.5">
                            <x-lucide name="user-check" class="w-3.5 h-3.5 text-[#1e3a8a]" />
                            Billed By (Issue Person Details)
                        </span>
                        <span class="text-[10px] text-zinc-400 font-medium">Appears below address on invoice</span>
                    </div>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                        <div>
                            <label for="issuer_name" class="block text-[11px] font-semibold text-zinc-600 mb-1">Issue Person Name</label>
                            <input 
                                type="text" 
                                id="issuer_name" 
                                wire:model.live.debounce.300ms="issuer_name" 
                                placeholder="e.g. Hussein Mwamba"
                                class="w-full text-xs rounded-lg border border-zinc-200 bg-white px-3 py-2 text-zinc-900 placeholder:text-zinc-400 focus:border-[#1e3a8a] focus:ring-1 focus:ring-[#1e3a8a] transition"
                            />
                        </div>
                        <div>
                            <label for="issuer_phone" class="block text-[11px] font-semibold text-zinc-600 mb-1">Issue Person Phone</label>
                            <input 
                                type="text" 
                                id="issuer_phone" 
                                wire:model.live.debounce.300ms="issuer_phone" 
                                placeholder="e.g. +255 754 889 912"
                                class="w-full text-xs rounded-lg border border-zinc-200 bg-white px-3 py-2 text-zinc-900 placeholder:text-zinc-400 focus:border-[#1e3a8a] focus:ring-1 focus:ring-[#1e3a8a] transition"
                            />
                        </div>
                    </div>
                </div>

                {{-- Items Details Section --}}
                <div class="space-y-3 pt-2">
                    <div class="flex items-center justify-between">
                        <label class="block text-xs font-semibold text-zinc-700">Items Details *</label>
                        <span class="text-[11px] text-zinc-400">Currency: TZS (Tanzanian Shillings)</span>
                    </div>

                    <div class="border border-zinc-200/80 rounded-xl overflow-hidden divide-y divide-zinc-100">
                        {{-- Repeater Header --}}
                        <div class="bg-zinc-50/70 px-3 py-2 text-[11px] font-semibold text-zinc-500 uppercase tracking-wider grid grid-cols-12 gap-2">
                            <div class="col-span-5">Item / Tyre Description</div>
                            <div class="col-span-2 text-center">QTY</div>
                            <div class="col-span-2 text-right">Cost (TZS)</div>
                            <div class="col-span-2 text-right">Amount (TZS)</div>
                            <div class="col-span-1 text-center"></div>
                        </div>

                        {{-- Repeater Rows --}}
                        <div class="divide-y divide-zinc-100">
                            @foreach ($items as $index => $item)
                                <div class="px-3 py-2.5 grid grid-cols-12 gap-2 items-center hover:bg-zinc-50/50 transition">
                                    {{-- Item Description / Selector --}}
                                    <div class="col-span-5 space-y-1">
                                        <input 
                                            type="text" 
                                            wire:model.live.debounce.300ms="items.{{ $index }}.item_description"
                                            placeholder="Tyre model, size, brand"
                                            class="w-full text-xs rounded-lg border border-zinc-200 px-2.5 py-1.5 text-zinc-900 focus:border-[#1e3a8a] focus:ring-0"
                                        />
                                        @if(count($products) > 0)
                                            <select 
                                                wire:change="selectProduct({{ $index }}, $event.target.value)"
                                                class="w-full text-[11px] text-zinc-500 bg-zinc-50 border border-zinc-200 rounded px-1.5 py-0.5"
                                            >
                                                <option value="">-- Or pick stock tyre --</option>
                                                @foreach($products as $prod)
                                                    <option value="{{ $prod->id }}">{{ $prod->brand }} {{ $prod->size }} ({{ number_format($prod->unit_price_tzs) }} TZS)</option>
                                                @endforeach
                                            </select>
                                        @endif
                                    </div>

                                    {{-- QTY --}}
                                    <div class="col-span-2">
                                        <input 
                                            type="number" 
                                            min="1" 
                                            wire:model.live.debounce.150ms="items.{{ $index }}.quantity"
                                            class="w-full text-xs text-center rounded-lg border border-zinc-200 px-2 py-1.5 text-zinc-900 focus:border-[#1e3a8a] focus:ring-0"
                                        />
                                    </div>

                                    {{-- Unit Price --}}
                                    <div class="col-span-2">
                                        <input 
                                            type="number" 
                                            step="1000" 
                                            wire:model.live.debounce.150ms="items.{{ $index }}.unit_price"
                                            class="w-full text-xs text-right rounded-lg border border-zinc-200 px-2 py-1.5 text-zinc-900 focus:border-[#1e3a8a] focus:ring-0"
                                        />
                                    </div>

                                    {{-- Amount / Subtotal for line --}}
                                    <div class="col-span-2 text-right text-xs font-semibold text-zinc-900">
                                        {{ number_format((float) ($item['amount'] ?? 0)) }}
                                    </div>

                                    {{-- Delete Button --}}
                                    <div class="col-span-1 text-center">
                                        @if (count($items) > 1)
                                            <button 
                                                type="button" 
                                                wire:click="confirmRemoveItem({{ $index }})" 
                                                class="text-zinc-400 hover:text-rose-600 p-1 transition rounded-md hover:bg-rose-50 cursor-pointer"
                                                title="Remove Item"
                                            >
                                                <x-lucide name="trash-2" class="w-4 h-4 mx-auto" />
                                            </button>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    {{-- Add Item Button matching reference --}}
                    <div>
                        <button 
                            type="button" 
                            wire:click="addItem" 
                            class="inline-flex items-center text-xs font-semibold text-zinc-700 hover:text-[#0a192f] py-1.5 px-1 transition cursor-pointer"
                        >
                            <x-lucide name="plus-circle" class="w-3.5 h-3.5 mr-1.5 text-blue-600" />
                            Add Item
                        </button>
                    </div>
                </div>

                {{-- Flexible Tax & Discount Configuration Section --}}
                <div class="pt-3 pb-1 border-t border-zinc-100 space-y-3">
                    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2">
                        <label class="block text-xs font-bold text-zinc-800">Tax & VAT Configuration</label>
                        {{-- Tax Mode Selector: TAX Inclusive vs TAX Exclusive --}}
                        <div class="inline-flex p-1 bg-zinc-100 rounded-xl border border-zinc-200">
                            <button 
                                type="button" 
                                wire:click="setTaxType('inclusive')" 
                                class="px-3.5 py-1.5 text-xs font-bold rounded-lg transition-all duration-200 cursor-pointer btn-interactive {{ $tax_type === 'inclusive' ? 'bg-[#0a192f] text-white shadow-xs scale-[1.02]' : 'text-zinc-600 hover:text-zinc-900' }}"
                            >
                                TAX Inclusive
                            </button>
                            <button 
                                type="button" 
                                wire:click="setTaxType('exclusive')" 
                                class="px-3.5 py-1.5 text-xs font-bold rounded-lg transition-all duration-200 cursor-pointer btn-interactive {{ $tax_type === 'exclusive' ? 'bg-amber-600 text-white shadow-xs scale-[1.02]' : 'text-zinc-600 hover:text-zinc-900' }}"
                            >
                                TAX Exclusive
                            </button>
                        </div>
                    </div>

                    {{-- Discount, Tax Rate %, Calculated Tax, and Total Row --}}
                    <div class="grid grid-cols-1 sm:grid-cols-4 gap-3 pt-1">
                        {{-- Discount --}}
                        <div>
                            <label for="discount_tzs" class="block text-[11px] font-semibold text-zinc-700 mb-1">Discount (TZS)</label>
                            <div class="relative">
                                <span class="absolute inset-y-0 left-0 pl-2.5 flex items-center text-[10px] font-semibold text-zinc-400 pointer-events-none">TZS</span>
                                <input 
                                    type="number" 
                                    step="1000" 
                                    id="discount_tzs" 
                                    wire:model.live.debounce.200ms="discount_tzs" 
                                    class="w-full text-xs rounded-xl border border-zinc-200 pl-10 pr-2.5 py-2 text-zinc-900 focus:border-[#1e3a8a] focus:ring-0"
                                />
                            </div>
                        </div>

                        {{-- Tax Rate % --}}
                        <div>
                            <label for="tax_rate" class="block text-[11px] font-semibold text-zinc-700 mb-1">
                                Tax Rate (%)
                                @if($tax_type === 'exclusive')
                                    <span class="text-amber-700 font-bold">(0% Fixed)</span>
                                @endif
                            </label>
                            <div class="relative">
                                @if($tax_type === 'exclusive')
                                    <input 
                                        type="text" 
                                        value="0" 
                                        disabled 
                                        class="w-full text-xs rounded-xl border border-amber-200 bg-amber-50/50 px-3 py-2 text-amber-900 font-semibold cursor-not-allowed"
                                    />
                                    <span class="absolute inset-y-0 right-0 pr-3 flex items-center text-[11px] font-bold text-amber-600 pointer-events-none">%</span>
                                @else
                                    <input 
                                        type="number" 
                                        step="0.5" 
                                        min="0" 
                                        max="100" 
                                        id="tax_rate" 
                                        wire:model.live.debounce.200ms="tax_rate" 
                                        placeholder="e.g. 18"
                                        class="w-full text-xs rounded-xl border border-zinc-200 px-3 py-2 text-zinc-900 bg-white focus:border-[#1e3a8a] focus:ring-0"
                                    />
                                    <span class="absolute inset-y-0 right-0 pr-3 flex items-center text-[11px] font-bold text-zinc-400 pointer-events-none">%</span>
                                @endif
                            </div>
                            @if($tax_type === 'exclusive')
                                <span class="text-[10px] text-amber-700 font-medium mt-1 block">0% TAX applied (TAX Excluded)</span>
                            @else
                                <span class="text-[10px] text-zinc-500 mt-1 block">Specify VAT % included in prices</span>
                            @endif
                        </div>

                        {{-- Calculated Tax Amount --}}
                        <div>
                            <label class="block text-[11px] font-semibold text-zinc-700 mb-1">
                                Tax Amount
                                @if($tax_type === 'inclusive')
                                    <span class="text-blue-600 text-[9px] font-bold">(Included)</span>
                                @else
                                    <span class="text-amber-800 text-[9px] font-bold">(Excluded: 0 TZS)</span>
                                @endif
                            </label>
                            <div class="w-full text-xs rounded-xl border border-zinc-200 bg-zinc-50/70 px-3 py-2 text-zinc-900 flex items-center justify-between">
                                <span class="text-[10px] text-zinc-400 font-semibold">TZS</span>
                                <span class="font-bold {{ $tax_type === 'exclusive' ? 'text-zinc-400' : 'text-zinc-900' }}">{{ number_format((float) ($tax_amount_tzs ?? 0)) }}</span>
                            </div>
                        </div>

                        {{-- Total Amount --}}
                        <div>
                            <label class="block text-[11px] font-semibold text-zinc-700 mb-1">Final Total *</label>
                            <div class="w-full text-xs rounded-xl border border-[#0a192f] bg-[#0a192f] px-3 py-2 text-white flex items-center justify-between font-bold shadow-xs transition-all duration-300">
                                <span class="text-[10px] text-blue-400">TZS</span>
                                <span class="text-xs font-bold transition-all duration-200">{{ number_format((float) ($total_amount_tzs ?? 0)) }}</span>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Section to Add / Choose Payment Methods --}}
                <div class="pt-4 border-t border-zinc-100 space-y-3">
                    <div class="flex items-center justify-between">
                        <div>
                            <label class="block text-xs font-bold text-zinc-800">Settlement & Payments</label>
                            <span class="text-[11px] text-zinc-400">Choose which bank or mobile money channels appear on this invoice</span>
                        </div>

                        @if(auth()->user()?->isAdmin())
                            <button 
                                type="button" 
                                wire:click="$toggle('showAddPaymentModal')"
                                class="inline-flex items-center text-xs font-semibold text-[#1e3a8a] hover:text-[#1d4ed8] bg-blue-50 px-2.5 py-1 rounded-lg transition"
                            >
                                <x-lucide name="credit-card" class="w-3.5 h-3.5 mr-1" />
                                Add Payment Method
                            </button>
                        @endif
                    </div>

                    {{-- Payment methods list checkboxes --}}
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-2">
                        @foreach ($available_payment_methods as $pm)
                            <label class="flex items-center p-2.5 rounded-xl border {{ in_array($pm->id, $selected_payment_method_ids) ? 'border-blue-300 bg-blue-50/40 text-[#0a192f]' : 'border-zinc-200 bg-white text-zinc-600' }} text-xs font-medium cursor-pointer transition">
                                <input 
                                    type="checkbox" 
                                    value="{{ $pm->id }}" 
                                    wire:model.live="selected_payment_method_ids"
                                    class="rounded border-zinc-300 text-[#1e3a8a] focus:ring-[#1e3a8a] mr-2.5"
                                />
                                @if($pm->logo_url)
                                    <img src="{{ $pm->logo_url }}" alt="{{ $pm->name }}" class="w-6 h-6 object-contain rounded p-0.5 bg-white border border-zinc-200 mr-2 shrink-0">
                                @endif
                                <div class="truncate">
                                    <span class="font-bold block leading-tight truncate">{{ $pm->name }}</span>
                                    <span class="text-[10px] text-zinc-400 font-mono">{{ $pm->account_number_or_till }}</span>
                                </div>
                            </label>
                        @endforeach
                    </div>

                    {{-- Inline Add Payment Method Modal/Card (Admin Only) --}}
                    @if (auth()->user()?->isAdmin() && $showAddPaymentModal)
                        <div class="bg-zinc-50 p-4 rounded-xl border border-blue-200 space-y-3 text-xs">
                            <div class="flex items-center justify-between font-bold text-zinc-900">
                                <span>Add New Payment</span>
                                <button type="button" wire:click="$toggle('showAddPaymentModal')" class="text-zinc-400 hover:text-zinc-700">&times;</button>
                            </div>
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                                <div>
                                    <label class="block text-[10px] font-semibold text-zinc-600 mb-1">Name (e.g. Stanbic Bank)</label>
                                    <input type="text" wire:model="new_pm_name" class="w-full text-xs rounded-lg border border-zinc-200 px-2.5 py-1.5 bg-white">
                                    @error('new_pm_name') <span class="text-[10px] text-rose-500">{{ $message }}</span> @enderror
                                </div>
                                <div>
                                    <label class="block text-[10px] font-semibold text-zinc-600 mb-1">Account Number / Till</label>
                                    <input type="text" wire:model="new_pm_account" class="w-full text-xs rounded-lg border border-zinc-200 px-2.5 py-1.5 bg-white font-mono">
                                    @error('new_pm_account') <span class="text-[10px] text-rose-500">{{ $message }}</span> @enderror
                                </div>
                            </div>
                            <div class="flex justify-end space-x-2 pt-1">
                                <button type="button" wire:click="$toggle('showAddPaymentModal')" class="px-3 py-1 bg-white border border-zinc-200 rounded-lg text-zinc-600">Cancel</button>
                                <button type="button" wire:click="addPaymentMethodQuickly" class="px-3 py-1 bg-[#0a192f] text-white rounded-lg font-semibold">Save Channel</button>
                            </div>
                        </div>
                    @endif
                </div>

                {{-- Notes to Customer --}}
                <div class="pt-2">
                    <label for="notes" class="block text-xs font-semibold text-zinc-700 mb-1.5">Notes to Customer *</label>
                    <textarea 
                        id="notes" 
                        wire:model.live.debounce.300ms="notes" 
                        rows="2"
                        class="w-full text-xs rounded-xl border border-zinc-200 px-3.5 py-2.5 text-zinc-900 placeholder:text-zinc-400 focus:border-[#1e3a8a] focus:ring-1 focus:ring-[#1e3a8a] transition"
                        placeholder="Thank you for your trust. Please complete payment before due date..."
                    ></textarea>
                </div>
            </div>

            {{-- RIGHT COLUMN: Floating Live Document Preview --}}
            <div class="lg:col-span-5 space-y-4">
                <div class="flex items-center justify-between px-1">
                    <h2 class="text-base font-bold text-zinc-900 tracking-tight">Live Document Preview</h2>
                    <span class="text-[11px] text-zinc-400 font-medium">Auto-formatted for A4 & Thermal</span>
                </div>

                {{-- The Document Card: Replicating Reference Screenshot in Deep Navy Accent --}}
                <div class="bg-white rounded-2xl border border-zinc-200/90 shadow-md p-6 sm:p-8 text-zinc-800 text-[11px] leading-relaxed transition-all duration-300 relative overflow-hidden card-interactive" id="printable-invoice">
                    
                    {{-- Company Logo Watermark --}}
                    <div class="absolute inset-0 flex items-center justify-center pointer-events-none select-none z-0 overflow-hidden">
                        <img src="{{ asset('images/logo.png') }}" alt="" class="w-80 max-w-[65%] opacity-[0.04] object-contain grayscale transform -rotate-12">
                    </div>

                    {{-- Header with Logo --}}
                    <div class="relative z-10 flex items-start justify-between pb-6 border-b border-zinc-100">
                        <img 
                            src="{{ asset('images/logo.png') }}" 
                            alt="Anagkazo Autoparts Logo" 
                            class="h-12 sm:h-14 w-auto object-contain"
                        >

                        <div class="text-right">
                            @if($tax_type === 'exclusive')
                                <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-extrabold uppercase tracking-wider bg-amber-100 text-amber-900 border border-amber-300 mb-0.5">
                                    TAX EXCLUSIVE
                                </span>
                            @else
                                <span class="text-[10px] font-bold uppercase tracking-widest text-[#1e3a8a] block">TAX INVOICE</span>
                            @endif
                            <span class="text-xs font-mono font-bold text-zinc-900 block">{{ $invoice_number }}</span>
                        </div>
                    </div>

                    {{-- Issue Date / Due Date / Payment Terms row --}}
                    <div class="grid grid-cols-3 gap-2 py-4 border-b border-zinc-100 text-[11px]">
                        <div>
                            <span class="text-zinc-400 block text-[10px]">Issue Date</span>
                            <span class="font-medium text-zinc-800">{{ $issue_date ? \Carbon\Carbon::parse($issue_date)->format('d F Y') : '-' }}</span>
                        </div>
                        <div>
                            <span class="text-zinc-400 block text-[10px]">Due Date</span>
                            <span class="font-medium text-zinc-800">{{ $due_date ? \Carbon\Carbon::parse($due_date)->format('d F Y') : '-' }}</span>
                        </div>
                        <div>
                            <span class="text-zinc-400 block text-[10px]">Payment Terms</span>
                            <span class="font-medium text-zinc-800">{{ $payment_terms }}</span>
                        </div>
                    </div>

                    {{-- Well-Arranged Billed By / Billed To Section --}}
                    <div class="py-3.5 space-y-3 text-[11px] border-b border-zinc-100">
                        <div class="p-2.5 rounded-lg bg-zinc-50 border border-zinc-200/80">
                            <span class="text-zinc-400 block text-[9px] font-bold uppercase tracking-wider mb-1">Billed by (Supplier):</span>
                            <span class="font-bold text-zinc-950 block text-xs">Anagkazo Autoparts Ltd</span>
                            <p class="text-zinc-500 text-[10px] leading-tight mt-0.5">
                                Plot 42, Msimbazi & Uhuru Street, Kariakoo<br>
                                TIN: 142-984-712 | VRN: 40-029184-Z | Tel: +255 754 889 912
                            </p>
                            @if($issuer_name || $issuer_phone)
                                <div class="mt-1.5 pt-1 border-t border-zinc-200/60 flex items-center gap-1.5 text-[10px] text-[#1e3a8a] font-medium">
                                    <span>Issued by:</span>
                                    @if($issuer_name)
                                        <span class="font-bold text-zinc-900">{{ $issuer_name }}</span>
                                    @endif
                                    @if($issuer_name && $issuer_phone)
                                        <span class="text-zinc-300">•</span>
                                    @endif
                                    @if($issuer_phone)
                                        <span class="text-zinc-600 font-mono">{{ $issuer_phone }}</span>
                                    @endif
                                </div>
                            @endif
                        </div>

                        <div class="p-2.5 rounded-lg bg-zinc-50 border border-zinc-200/80">
                            <span class="text-zinc-400 block text-[9px] font-bold uppercase tracking-wider mb-1">Billed to (Customer):</span>
                            @php
                                $selectedCustomer = $customer_id ? \App\Models\Customer::find($customer_id) : null;
                                $calculatedTier = $selectedCustomer ? $selectedCustomer->tier : ($total_amount_tzs >= 100000000 ? 'premium' : ($total_amount_tzs >= 50000000 ? 'medium' : 'standard'));
                            @endphp
                            <div class="flex items-center gap-1.5 flex-wrap">
                                <span class="font-bold text-zinc-950 block text-xs">{{ $customer_name ?: 'Valued Customer' }}</span>
                                @if($calculatedTier === 'premium')
                                    <span class="inline-flex items-center gap-1 px-1.5 py-0.2 rounded text-[9px] font-black bg-amber-100 text-amber-950 border border-amber-300 shadow-2xs">
                                        <x-lucide name="crown" class="w-3 h-3 text-amber-600 fill-amber-500" />
                                        Premium
                                    </span>
                                @elseif($calculatedTier === 'medium')
                                    <span class="inline-flex items-center gap-1 px-1.5 py-0.2 rounded text-[9px] font-bold bg-blue-50 text-blue-900 border border-blue-200">
                                        <x-lucide name="award" class="w-3 h-3 text-blue-600" />
                                        Medium
                                    </span>
                                @endif
                            </div>
                            <p class="text-zinc-500 text-[10px] whitespace-pre-line leading-tight mt-1">
                                {{ $billing_address ?: 'Kariakoo Commercial District, Dar es Salaam' }}
                            </p>
                        </div>
                    </div>

                    {{-- Itemized Table --}}
                    <div class="py-2 border-t border-zinc-100">
                        <table class="w-full text-[11px]">
                            <thead>
                                <tr class="text-zinc-400 border-b border-zinc-100 text-[10px]">
                                    <th class="text-left font-normal py-2">Item</th>
                                    <th class="text-center font-normal py-2">QTY</th>
                                    <th class="text-right font-normal py-2">Cost</th>
                                    <th class="text-right font-normal py-2">Amount</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-zinc-50">
                                @foreach ($items as $item)
                                    <tr>
                                        <td class="py-2.5 text-zinc-900 font-medium max-w-[140px] truncate">
                                            {{ $item['item_description'] ?: 'Tyre Item' }}
                                        </td>
                                        <td class="py-2.5 text-center text-zinc-600">
                                            {{ $item['quantity'] }} {{ $item['unit_label'] ?? 'pcs' }}
                                        </td>
                                        <td class="py-2.5 text-right text-zinc-600 font-mono text-[10px]">
                                            TZS {{ number_format((float) ($item['unit_price'] ?? 0)) }}
                                        </td>
                                        <td class="py-2.5 text-right text-zinc-900 font-semibold font-mono text-[10px]">
                                            TZS {{ number_format((float) ($item['amount'] ?? 0)) }}
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    {{-- Settlement & Totals Grid with Dynamic Payment Methods and Logos --}}
                    <div class="pt-4 border-t border-zinc-100 grid grid-cols-2 gap-4 text-[11px]">
                        {{-- Left Side: Dynamic Payments with Logos --}}
                        <div class="text-[10px] space-y-2 text-zinc-500">
                            <span class="font-bold text-[#0a192f] block text-[11px]">Payments & Settlement:</span>
                            @forelse ($selected_methods as $m)
                                <div class="flex items-center gap-2.5 p-1.5 rounded-lg bg-zinc-50 border border-zinc-100">
                                    @if($m->logo_url)
                                        <div class="w-7 h-7 rounded-md bg-white border border-zinc-200/80 p-0.5 flex items-center justify-center shrink-0 shadow-2xs">
                                            <img src="{{ $m->logo_url }}" alt="{{ $m->name }}" class="max-h-full max-w-full object-contain">
                                        </div>
                                    @else
                                        <div class="w-7 h-7 rounded-md bg-blue-50 text-[#1e3a8a] border border-blue-100 flex items-center justify-center shrink-0 font-bold">
                                            <x-lucide name="{{ $m->type === 'mobile_money' ? 'phone' : 'building-2' }}" class="w-3.5 h-3.5" />
                                        </div>
                                    @endif
                                    <div class="leading-tight">
                                        <span class="font-bold text-zinc-900 block text-[10px]">{{ $m->name }}</span>
                                        <span class="font-mono text-[9px] text-zinc-600">{{ $m->type === 'mobile_money' ? 'Till: ' : 'A/C: ' }}<strong class="text-zinc-900">{{ $m->account_number_or_till }}</strong></span>
                                    </div>
                                </div>
                            @empty
                                <div>
                                    <span class="font-medium text-zinc-800">CRDB Bank (Kariakoo)</span><br>
                                    A/C: 0150294827100
                                </div>
                                <div>
                                    <span class="font-medium text-zinc-800">M-Pesa Lipa Namba</span><br>
                                    Till: 5829104
                                </div>
                            @endforelse
                        </div>

                        {{-- Right Side: Subtotal, Discount, Tax, Total --}}
                        <div class="space-y-1.5 text-right">
                            <div class="flex justify-between text-zinc-500">
                                <span>Subtotal</span>
                                <span class="font-mono text-zinc-800">TZS {{ number_format((float) ($subtotal_tzs ?? 0)) }}</span>
                            </div>
                            <div class="flex justify-between text-zinc-500">
                                <span>Discount</span>
                                <span class="font-mono text-zinc-800">-TZS {{ number_format((float) ($discount_tzs ?? 0)) }}</span>
                            </div>
                            <div class="flex justify-between text-zinc-500">
                                @if($tax_type === 'inclusive')
                                    <span>TAX ({{ (float) ($tax_rate ?? 0) }}%)</span>
                                    <span class="font-mono text-zinc-800">+TZS {{ number_format((float) ($tax_amount_tzs ?? 0)) }}</span>
                                @else
                                    <span class="font-bold text-amber-900">TAX Exclusive (0%)</span>
                                    <span class="font-bold text-zinc-600">TZS 0</span>
                                @endif
                            </div>
                            <div class="flex justify-between text-zinc-950 font-bold pt-1.5 border-t border-zinc-200 text-xs">
                                <span>Total</span>
                                <span class="font-mono text-zinc-950">TZS {{ number_format((float) ($total_amount_tzs ?? 0)) }}</span>
                            </div>
                        </div>
                    </div>

                    {{-- Notes and Managing Director Signature Section --}}
                    <div class="mt-6 pt-4 border-t border-zinc-100 flex flex-col sm:flex-row sm:items-end justify-between gap-4">
                        <div class="text-[10px] text-zinc-500 max-w-xs">
                            <span class="font-semibold text-zinc-700 block mb-0.5">Notes & Terms</span>
                            <p class="leading-relaxed">
                                {{ $notes }}
                            </p>
                        </div>
                        <div class="relative text-right flex flex-col items-end shrink-0 min-w-[140px]">
                            <span class="text-[9px] text-zinc-400 font-semibold uppercase tracking-wider block mb-0.5 z-10">Approved & Authorized by:</span>
                            <div class="relative flex items-center justify-center my-0.5">
                                <img 
                                    src="{{ asset('images/official-stamp.png') }}" 
                                    alt="Anagkazo Official Stamp" 
                                    class="w-20 h-20 sm:w-24 sm:h-24 object-contain opacity-90 absolute -top-4 pointer-events-none transform -rotate-6 select-none"
                                >
                                <div class="font-pinyon-script text-2xl sm:text-sm text-zinc-950 font-normal leading-none py-1 select-none z-10">
                                    Joseph Matemba
                                </div>
                            </div>
                            <span class="text-[9px] text-zinc-500 font-semibold uppercase tracking-wider block z-10">Managing Director</span>
                        </div>
                    </div>

                </div>

                {{-- Quick Distribution Actions Toolbar --}}
                <div class="bg-white rounded-xl border border-zinc-200/90 p-4 shadow-2xs flex flex-wrap items-center justify-between gap-3">
                    <div class="text-xs font-semibold text-zinc-700">Quick Dispatch</div>
                    <div class="flex items-center space-x-2">
                        @php
                            $waText = urlencode("Hello {$customer_name}, here is your invoice from Anagkazo Tyres ({$invoice_number}) totaling TZS " . number_format($total_amount_tzs) . ". Thank you for your business!");
                        @endphp
                        <a 
                            href="https://wa.me/?text={{ $waText }}" 
                            target="_blank" 
                            class="inline-flex items-center px-3 py-1.5 text-xs font-medium text-emerald-700 bg-emerald-50 hover:bg-emerald-100 rounded-lg border border-emerald-200 transition btn-interactive"
                        >
                            <x-lucide name="message-circle" class="w-3.5 h-3.5 mr-1 text-emerald-600" />
                            WhatsApp
                        </a>
                    </div>
                </div>

            </div>

        </div>
    </main>

    {{-- Thermal & A4 Print CSS --}}
    <style>
        @media print {
            body {
                background: white !important;
                padding: 0 !important;
            }
            header, .lg\:col-span-7, .text-xs.font-semibold.text-zinc-700, .bg-white.rounded-xl.border.border-zinc-200\/90.p-4, .flex.flex-col.sm\:flex-row, button {
                display: none !important;
            }
            .lg\:col-span-5 {
                width: 100% !important;
                max-width: 100% !important;
            }
            #printable-invoice {
                border: none !important;
                box-shadow: none !important;
                padding: 0 !important;
            }
        }
    </style>

    {{-- DELETE LINE ITEM CONFIRMATION MODAL --}}
    @if ($showDeleteItemModal)
        <div class="fixed inset-0 bg-black/60 backdrop-blur-xs z-50 flex items-center justify-center p-4 animate-fade-in">
            <div class="bg-white rounded-2xl max-w-md w-full p-6 shadow-2xl border border-zinc-200 animate-scale-up">
                <div class="w-12 h-12 rounded-full bg-rose-50 text-rose-600 flex items-center justify-center mx-auto mb-4 border border-rose-100">
                    <x-lucide name="alert-triangle" class="w-6 h-6" />
                </div>

                <h3 class="text-center text-lg font-bold text-zinc-950">Remove Line Item</h3>
                <p class="text-center text-xs text-zinc-500 mt-2 leading-relaxed">
                    Are you sure you want to remove <strong class="text-zinc-800">{{ $deletingItemName }}</strong> from this invoice?
                </p>

                <div class="mt-6 flex items-center justify-center space-x-3">
                    <button 
                        type="button" 
                        wire:click="cancelRemoveItem" 
                        class="px-4 py-2 text-xs font-semibold text-zinc-700 bg-zinc-100 hover:bg-zinc-200 rounded-xl transition cursor-pointer btn-interactive"
                    >
                        Cancel
                    </button>
                    <button 
                        type="button" 
                        wire:click="removeItem" 
                        class="px-5 py-2 text-xs font-bold text-white bg-rose-600 hover:bg-rose-700 rounded-xl transition cursor-pointer shadow-xs btn-interactive"
                    >
                        Yes, Remove Item
                    </button>
                </div>
            </div>
        </div>
    @endif

    <x-page-footer />
</div>
