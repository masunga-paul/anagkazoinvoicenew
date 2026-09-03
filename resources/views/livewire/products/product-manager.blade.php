<div wire:poll.4s class="min-h-screen bg-[#f3f4f6] text-zinc-900 py-6 px-4 sm:px-6 lg:px-8 font-sans antialiased">
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
                <a href="{{ route('products.index') }}" wire:navigate class="whitespace-nowrap px-3 py-1.5 text-xs font-semibold rounded-xl transition bg-[#0a192f] text-white shadow-xs">Stocks</a>
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
    </header>

    <main class="max-w-7xl mx-auto space-y-6">
        {{-- Flash Messages --}}
        @if (session()->has('success'))
            <div class="p-4 rounded-xl bg-blue-50 border border-blue-200 text-blue-900 flex items-center justify-between gap-3 shadow-xs animate-slide-down">
                <div class="flex items-center gap-3">
                    <x-lucide name="check-circle-2" class="w-5 h-5 text-blue-600 shrink-0" />
                    <span class="text-xs font-semibold">{{ session('success') }}</span>
                </div>
            </div>
        @endif

        @if (session()->has('error'))
            <div class="p-4 rounded-xl bg-rose-50 border border-rose-200 text-rose-900 flex items-center justify-between gap-3 shadow-xs animate-slide-down">
                <div class="flex items-center gap-3">
                    <x-lucide name="alert-triangle" class="w-5 h-5 text-rose-600 shrink-0" />
                    <span class="text-xs font-semibold">{{ session('error') }}</span>
                </div>
            </div>
        @endif

        {{-- Top Inventory KPI Cards --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 {{ auth()->user()?->isAdmin() ? 'lg:grid-cols-4' : 'lg:grid-cols-3' }} gap-5">
            @if(auth()->user()?->isAdmin())
                <div class="bg-white rounded-2xl p-6 border border-zinc-200/90 shadow-xs card-interactive">
                    <div class="flex items-center justify-between text-zinc-500 mb-2">
                        <span class="text-xs font-bold uppercase tracking-wider">Depot Inventory Value</span>
                        <div class="w-8 h-8 rounded-lg bg-blue-50 flex items-center justify-center text-[#1e3a8a]">
                            <x-lucide name="wallet" class="w-4 h-4" />
                        </div>
                    </div>
                    <div class="text-2xl font-black text-[#0a192f] tracking-tight">TZS {{ number_format($totalValuation) }}</div>
                    <span class="text-[11px] text-zinc-400 mt-1 block">Selling valuation across warehouse</span>
                </div>
            @endif

            <div class="bg-white rounded-2xl p-6 border border-zinc-200/90 shadow-xs card-interactive">
                <div class="flex items-center justify-between text-zinc-500 mb-2">
                    <span class="text-xs font-bold uppercase tracking-wider">Total Tyres In Store</span>
                    <div class="w-8 h-8 rounded-lg bg-emerald-50 flex items-center justify-center text-emerald-600">
                        <x-lucide name="disc" class="w-4 h-4" />
                    </div>
                </div>
                <div class="text-2xl font-black text-emerald-600 tracking-tight">{{ number_format($totalUnits) }} Tyres</div>
                <span class="text-[11px] text-zinc-400 mt-1 block">Available for instant delivery</span>
            </div>

            <div class="bg-white rounded-2xl p-6 border border-zinc-200/90 shadow-xs">
                <div class="flex items-center justify-between text-zinc-500 mb-2">
                    <span class="text-xs font-bold uppercase tracking-wider">Active Tyre SKUs</span>
                    <div class="w-8 h-8 rounded-lg bg-zinc-100 flex items-center justify-center text-zinc-700">
                        <x-lucide name="layers" class="w-4 h-4" />
                    </div>
                </div>
                <div class="text-2xl font-black text-zinc-900 tracking-tight">{{ $totalSkus }} Sizes & Patterns</div>
                <span class="text-[11px] text-zinc-400 mt-1 block">TBR, SUV 4x4, & PCR profiles</span>
            </div>

            <div class="bg-white rounded-2xl p-6 border border-zinc-200/90 shadow-xs">
                <div class="flex items-center justify-between text-zinc-500 mb-2">
                    <span class="text-xs font-bold uppercase tracking-wider">Stock Reorder Alerts</span>
                    <div class="w-8 h-8 rounded-lg bg-rose-50 flex items-center justify-center text-rose-600">
                        <x-lucide name="alert-triangle" class="w-4 h-4" />
                    </div>
                </div>
                <div class="text-2xl font-black text-rose-600 tracking-tight">{{ $lowStockCount }} Profiles</div>
                <span class="text-[11px] text-zinc-400 mt-1 block">Below safety reorder threshold</span>
            </div>
        </div>

        {{-- Toolbar: Search, Filters & View Toggle --}}
        <div class="bg-white rounded-2xl border border-zinc-200/90 p-4 shadow-xs flex flex-col md:flex-row items-center justify-between gap-4">
            <div class="flex flex-col sm:flex-row items-center gap-3 w-full md:w-auto">
                {{-- Search Box --}}
                <div class="relative w-full sm:w-80">
                    <x-lucide name="search" class="w-4 h-4 text-zinc-400 absolute left-3.5 top-1/2 -translate-y-1/2" />
                    <input 
                        type="text" 
                        wire:model.live.debounce.300ms="search" 
                        placeholder="Search brand, tyre size, pattern, SKU..." 
                        class="w-full text-xs pl-9 pr-4 py-2 rounded-xl border border-zinc-200 text-zinc-900 focus:border-[#1e3a8a] focus:ring-0"
                    />
                </div>

                {{-- Category Filter --}}
                <select wire:model.live="categoryFilter" class="w-full sm:w-auto text-xs rounded-xl border border-zinc-200 px-3 py-2 text-zinc-700 bg-white focus:border-[#1e3a8a]">
                    <option value="all">All Categories ({{ count($availableCategories) }})</option>
                    @foreach ($availableCategories as $cat)
                        <option value="{{ $cat }}">{{ $cat }}</option>
                    @endforeach
                </select>

                {{-- Stock Filter --}}
                <select wire:model.live="stockFilter" class="w-full sm:w-auto text-xs rounded-xl border border-zinc-200 px-3 py-2 text-zinc-700 bg-white focus:border-[#1e3a8a]">
                    <option value="all">All Stock Status</option>
                    <option value="in_stock">In Stock</option>
                    <option value="low_stock">Low Stock (Reorder)</option>
                    <option value="out_of_stock">Out of Stock</option>
                </select>
            </div>

            <div class="flex items-center gap-2 w-full md:w-auto justify-end">
                {{-- View Toggle --}}
                <div class="flex items-center bg-zinc-100 p-1 rounded-xl border border-zinc-200/60 text-xs">
                    <button 
                        type="button" 
                        wire:click="$set('viewMode', 'grid')" 
                        class="px-3 py-1 rounded-lg font-semibold transition cursor-pointer {{ $viewMode === 'grid' ? 'bg-white text-[#0a192f] shadow-xs' : 'text-zinc-500 hover:text-zinc-900' }}"
                    >
                        Grid
                    </button>
                    <button 
                        type="button" 
                        wire:click="$set('viewMode', 'table')" 
                        class="px-3 py-1 rounded-lg font-semibold transition cursor-pointer {{ $viewMode === 'table' ? 'bg-white text-[#0a192f] shadow-xs' : 'text-zinc-500 hover:text-zinc-900' }}"
                    >
                        Table
                    </button>
                </div>

                @if(auth()->user()?->isAdmin())
                    <button 
                        type="button" 
                        wire:click="openCreateModal" 
                        class="inline-flex items-center px-4 py-2 bg-[#0a192f] hover:bg-[#1e3a8a] text-white rounded-xl text-xs font-bold transition shadow-xs cursor-pointer btn-interactive"
                    >
                        <x-lucide name="package-plus" class="w-4 h-4 mr-1.5" />
                        Add new tyre
                    </button>
                @endif
            </div>
        </div>

        {{-- GRID VIEW --}}
        @if ($viewMode === 'grid')
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                @forelse ($products as $product)
                    <div class="bg-white rounded-2xl border border-zinc-200/90 overflow-hidden shadow-xs card-interactive flex flex-col justify-between group">
                        {{-- Image & Badges --}}
                        <div class="relative h-48 bg-zinc-100 overflow-hidden">
                            <img 
                                src="{{ $product->image_url ?: 'https://images.unsplash.com/photo-1578844251758-2f71da64c96f?w=600&auto=format&fit=crop&q=80' }}" 
                                alt="{{ $product->brand }} {{ $product->size }}" 
                                class="w-full h-full object-cover transition-transform duration-500 ease-out group-hover:scale-108"
                            />
                            
                            {{-- Brand Badge --}}
                            <div class="absolute top-3 left-3 bg-[#0a192f]/90 backdrop-blur-xs text-white text-[10px] font-bold px-2.5 py-1 rounded-full uppercase tracking-wider">
                                {{ $product->brand }}
                            </div>

                            {{-- Stock Status Pill --}}
                            @if ($product->stock_quantity <= 0)
                                <div class="absolute top-3 right-3 bg-zinc-800 text-white text-[10px] font-bold px-2.5 py-1 rounded-full uppercase tracking-wider">
                                    Out of Stock
                                </div>
                            @elseif ($product->stock_quantity <= $product->reorder_threshold)
                                <div class="absolute top-3 right-3 bg-rose-600 text-white text-[10px] font-bold px-2.5 py-1 rounded-full uppercase tracking-wider flex items-center gap-1">
                                    <x-lucide name="alert-triangle" class="w-3 h-3" />
                                    Low Stock: {{ $product->stock_quantity }}
                                </div>
                            @else
                                <div class="absolute top-3 right-3 bg-emerald-600 text-white text-[10px] font-bold px-2.5 py-1 rounded-full uppercase tracking-wider">
                                    In Stock: {{ $product->stock_quantity }}
                                </div>
                            @endif
                        </div>

                        {{-- Details Body --}}
                        <div class="p-5 flex-1 flex flex-col justify-between space-y-4">
                            <div>
                                <div class="flex items-center justify-between">
                                    <span class="text-xs font-bold text-[#1e3a8a]">{{ $product->size }}</span>
                                    <span class="text-[10px] text-zinc-400 font-bold uppercase">{{ $product->sku }}</span>
                                </div>
                                <h3 class="font-bold text-base text-zinc-950 mt-0.5">{{ $product->pattern }}</h3>
                                <div class="flex items-center justify-between mt-0.5">
                                    <span class="text-[11px] text-zinc-400 capitalize">{{ str_replace('_', ' ', $product->category) }}</span>
                                    <span class="text-[10px] text-zinc-400">Added: {{ $product->created_at ? $product->created_at->format('M d, Y, h:i A') : 'N/A' }}</span>
                                </div>
                            </div>

                            {{-- Quick Stock Counter Adjuster --}}
                            <div class="bg-zinc-50 p-2.5 rounded-xl border border-zinc-100 flex items-center justify-between">
                                <span class="text-xs font-semibold text-zinc-600">Depot Stock:</span>
                                @if(auth()->user()?->isAdmin())
                                    <div class="flex items-center space-x-2">
                                        <button 
                                            type="button" 
                                            wire:click="quickStockChange({{ $product->id }}, -1)"
                                            class="w-6 h-6 rounded-md bg-white border border-zinc-200 text-zinc-700 hover:bg-zinc-100 flex items-center justify-center text-xs font-bold cursor-pointer"
                                            title="Deduct 1 tyre"
                                        >
                                            -
                                        </button>
                                        <span class="text-xs font-extrabold text-[#0a192f] w-8 text-center">{{ $product->stock_quantity }}</span>
                                        <button 
                                            type="button" 
                                            wire:click="quickStockChange({{ $product->id }}, 1)"
                                            class="w-6 h-6 rounded-md bg-white border border-zinc-200 text-zinc-700 hover:bg-zinc-100 flex items-center justify-center text-xs font-bold cursor-pointer"
                                            title="Add 1 tyre"
                                        >
                                            +
                                        </button>
                                        <button 
                                            type="button" 
                                            wire:click="openAdjustModal({{ $product->id }})"
                                            class="text-[10px] font-semibold text-[#1e3a8a] hover:underline ml-1 cursor-pointer"
                                        >
                                            Intake
                                        </button>
                                    </div>
                                @else
                                    <span class="text-xs font-extrabold text-[#0a192f]">{{ $product->stock_quantity }} Available</span>
                                @endif
                            </div>

                            {{-- Price & Actions --}}
                            <div class="pt-2 border-t border-zinc-100 flex items-center justify-between">
                                <div>
                                    <span class="text-[10px] text-zinc-400 uppercase font-semibold block">Selling Price</span>
                                    <span class="text-base font-black text-zinc-950">TZS {{ number_format($product->unit_price_tzs) }}</span>
                                </div>

                                @if(auth()->user()?->isAdmin())
                                    <div class="flex items-center space-x-1">
                                        <button 
                                            type="button" 
                                            wire:click="openEditModal({{ $product->id }})"
                                            class="p-2 text-zinc-500 hover:text-[#0a192f] hover:bg-zinc-100 rounded-lg transition cursor-pointer"
                                            title="Edit Tyre Details"
                                        >
                                            <x-lucide name="pencil" class="w-4 h-4 text-zinc-600" />
                                        </button>

                                        <button 
                                            type="button" 
                                            wire:click="confirmDelete({{ $product->id }})"
                                            class="p-2 text-zinc-400 hover:text-rose-600 hover:bg-rose-50 rounded-lg transition cursor-pointer"
                                            title="Delete Tyre"
                                        >
                                            <x-lucide name="trash-2" class="w-4 h-4" />
                                        </button>
                                    </div>
                                @else
                                    <a 
                                        href="{{ route('invoices.create') }}" 
                                        class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg bg-blue-50 hover:bg-[#1e3a8a] text-[#1e3a8a] hover:text-white text-xs font-bold transition shadow-2xs"
                                        title="Invoice this Tyre SKU"
                                    >
                                        <x-lucide name="file-plus" class="w-3.5 h-3.5" />
                                        <span>Issue in Invoice</span>
                                    </a>
                                @endif
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-span-3 bg-white rounded-2xl p-12 text-center text-zinc-400 border border-zinc-200">
                        <x-lucide name="disc" class="w-10 h-10 mx-auto mb-3 text-zinc-300" />
                        <p class="text-sm font-semibold text-zinc-600">No tyre models found matching your search.</p>
                        @if(auth()->user()?->isAdmin())
                            <button type="button" wire:click="openCreateModal" class="mt-3 inline-flex items-center px-4 py-2 text-xs font-bold text-white bg-[#0a192f] rounded-xl shadow-xs">
                                <x-lucide name="package-plus" class="w-4 h-4 mr-1 text-blue-400" />
                                Add first tyre
                            </button>
                        @endif
                    </div>
                @endforelse
            </div>
        @else
            {{-- TABLE VIEW --}}
            <div class="bg-white rounded-2xl border border-zinc-200/90 shadow-xs overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-xs">
                        <thead class="bg-zinc-50/70 border-b border-zinc-100 text-[10px] font-semibold text-zinc-500 uppercase tracking-wider">
                            <tr>
                                <th class="px-6 py-3.5">Tyre Product & Brand</th>
                                <th class="px-6 py-3.5">Size Profile</th>
                                <th class="px-6 py-3.5">Category</th>
                                <th class="px-6 py-3.5 text-right">Selling Price</th>
                                <th class="px-6 py-3.5 text-center">Depot Stock</th>
                                <th class="px-6 py-3.5 text-right">Stock Value</th>
                                <th class="px-6 py-3.5 text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-zinc-100">
                            @forelse ($products as $product)
                                <tr class="hover:bg-zinc-50/60 transition">
                                    <td class="px-6 py-4 flex items-center space-x-3">
                                        <img class="w-10 h-10 rounded-lg object-cover ring-1 ring-zinc-200" src="{{ $product->image_url }}" alt="{{ $product->brand }}">
                                        <div>
                                            <span class="font-bold text-zinc-950 block">{{ $product->brand }}</span>
                                            <span class="text-[11px] text-zinc-400">{{ $product->pattern }} • <span class="font-bold">{{ $product->sku }}</span></span>
                                            <span class="text-[10px] text-zinc-400 block mt-0.5">Added: {{ $product->created_at ? $product->created_at->format('M d, Y, h:i A') : 'N/A' }}</span>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 font-bold text-[#1e3a8a]">
                                        {{ $product->size }}
                                    </td>
                                    <td class="px-6 py-4 capitalize text-zinc-600">
                                        {{ str_replace('_', ' ', $product->category) }}
                                    </td>
                                    <td class="px-6 py-4 text-right font-bold text-zinc-900">
                                        TZS {{ number_format($product->unit_price_tzs) }}
                                    </td>
                                    <td class="px-6 py-4 text-center">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-bold {{ $product->stock_quantity <= $product->reorder_threshold ? 'bg-rose-50 text-rose-700 border border-rose-200' : 'bg-emerald-50 text-emerald-700 border border-emerald-200' }}">
                                            {{ $product->stock_quantity }} pcs
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 text-right font-bold text-[#0a192f]">
                                        TZS {{ number_format($product->stock_quantity * $product->unit_price_tzs) }}
                                    </td>
                                    <td class="px-6 py-4 text-right space-x-1">
                                        @if(auth()->user()?->isAdmin())
                                            <button 
                                                type="button" 
                                                wire:click="openEditModal({{ $product->id }})"
                                                class="inline-flex items-center px-2.5 py-1 text-xs font-semibold text-zinc-700 bg-white border border-zinc-200 rounded-lg hover:bg-zinc-50 transition cursor-pointer"
                                            >
                                                <x-lucide name="pencil" class="w-3.5 h-3.5 mr-1 text-zinc-500" />
                                                Edit
                                            </button>
                                            <button 
                                                type="button" 
                                                wire:click="openAdjustModal({{ $product->id }})"
                                                class="inline-flex items-center px-2.5 py-1 text-xs font-semibold text-[#1e3a8a] bg-blue-50 border border-blue-200 rounded-lg hover:bg-blue-100 transition cursor-pointer"
                                            >
                                                + Intake
                                            </button>
                                            <button 
                                                type="button" 
                                                wire:click="confirmDelete({{ $product->id }})"
                                                class="inline-flex items-center px-2 py-1 text-xs font-semibold text-rose-600 hover:bg-rose-50 rounded-lg transition cursor-pointer"
                                                title="Delete Tyre"
                                            >
                                                <x-lucide name="trash-2" class="w-3.5 h-3.5" />
                                            </button>
                                        @else
                                            <a 
                                                href="{{ route('invoices.create') }}" 
                                                class="inline-flex items-center px-2.5 py-1 bg-blue-50 text-[#1e3a8a] hover:bg-[#1e3a8a] hover:text-white text-xs font-semibold rounded-lg transition shadow-2xs"
                                            >
                                                <x-lucide name="file-plus" class="w-3.5 h-3.5 mr-1" />
                                                Invoice SKU
                                            </a>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="px-6 py-12 text-center text-zinc-400">
                                        No tyre models found.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        @endif

        {{-- Pagination --}}
        <div class="pt-2">
            {{ $products->links() }}
        </div>

        {{-- CREATE / EDIT PRODUCT MODAL --}}
        @if ($showProductModal)
            <div class="fixed inset-0 bg-black/60 backdrop-blur-xs z-50 flex items-center justify-center p-4 overflow-y-auto animate-fade-in">
                <div class="bg-white rounded-3xl max-w-2xl w-full p-6 sm:p-8 space-y-6 shadow-2xl relative my-8 border border-zinc-100 animate-scale-up">
                    <div class="flex items-center justify-between pb-4 border-b border-zinc-100">
                        <div>
                            <h3 class="text-xl font-extrabold text-zinc-950">
                                {{ $isEditing ? 'Edit Tyre Product' : 'Add new tyre' }}
                            </h3>
                            <p class="text-xs text-zinc-400">Specify tyre sizing, category profile, depot stock and selling rates.</p>
                        </div>
                        <button type="button" wire:click="$set('showProductModal', false)" class="text-zinc-400 hover:text-zinc-700 p-1.5 rounded-lg hover:bg-zinc-100 btn-interactive">
                            <x-lucide name="x" class="w-5 h-5" />
                        </button>
                    </div>

                    <form wire:submit="saveProduct" novalidate class="space-y-4 text-xs">
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label class="block font-bold text-zinc-700 mb-1">Brand Name *</label>
                                <input type="text" wire:model="brand" placeholder="e.g. Triangle, Bridgestone, Maxxis" class="w-full rounded-xl border border-zinc-200 px-3.5 py-2.5 text-xs focus:border-[#1e3a8a]">
                                @error('brand') <span class="text-rose-500 text-[10px] mt-0.5 block">{{ $message }}</span> @enderror
                            </div>

                            <div>
                                <label class="block font-bold text-zinc-700 mb-1">Tyre Size *</label>
                                <input type="text" wire:model="size" placeholder="e.g. 315/80R22.5 or 265/65R17" class="w-full rounded-xl border border-zinc-200 px-3.5 py-2.5 text-xs focus:border-[#1e3a8a]">
                                @error('size') <span class="text-rose-500 text-[10px] mt-0.5 block">{{ $message }}</span> @enderror
                            </div>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label class="block font-bold text-zinc-700 mb-1">Tread Pattern / Model *</label>
                                <input type="text" wire:model="pattern" placeholder="e.g. TR668 All-Position or Dueler A/T" class="w-full rounded-xl border border-zinc-200 px-3.5 py-2.5 text-xs focus:border-[#1e3a8a]">
                                @error('pattern') <span class="text-rose-500 text-[10px] mt-0.5 block">{{ $message }}</span> @enderror
                            </div>

                            <div>
                                <label class="block font-bold text-zinc-700 mb-1">Category (Choose or Type Manually) *</label>
                                <input 
                                    type="text" 
                                    list="tyreCategoriesList" 
                                    wire:model="category" 
                                    placeholder="e.g. Truck & Bus Radial (TBR), SUV 4x4, Agricultural"
                                    class="w-full rounded-xl border border-zinc-200 px-3.5 py-2.5 text-xs focus:border-[#1e3a8a]"
                                />
                                <datalist id="tyreCategoriesList">
                                    @foreach ($availableCategories as $cat)
                                        <option value="{{ $cat }}"></option>
                                    @endforeach
                                    <option value="Truck & Bus Radial (TBR)"></option>
                                    <option value="Passenger Car (PCR)"></option>
                                    <option value="SUV & 4x4 Off-Road"></option>
                                    <option value="Industrial / Agricultural / OTR"></option>
                                    <option value="Light Commercial Vehicle (LCV)"></option>
                                    <option value="Heavy Trailer & Lowbed"></option>
                                    <option value="Motorcycle & Three-Wheeler (Bajaj)"></option>
                                </datalist>
                                <p class="text-[10px] text-zinc-400 mt-1">Select from suggestions or type any custom category name.</p>
                                @error('category') <span class="text-rose-500 text-[10px] mt-0.5 block">{{ $message }}</span> @enderror
                            </div>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                            <div>
                                <label class="block font-bold text-zinc-700 mb-1">SKU / Barcode *</label>
                                <input type="text" wire:model="sku" class="w-full rounded-xl border border-zinc-200 px-3.5 py-2.5 text-xs uppercase focus:border-[#1e3a8a]">
                                @error('sku') <span class="text-rose-500 text-[10px] mt-0.5 block">{{ $message }}</span> @enderror
                            </div>

                            <div>
                                <label class="block font-bold text-zinc-700 mb-1">Unit Price (TZS) *</label>
                                <input type="number" step="1000" wire:model="unit_price_tzs" class="w-full rounded-xl border border-zinc-200 px-3.5 py-2.5 text-xs focus:border-[#1e3a8a]">
                                @error('unit_price_tzs') <span class="text-rose-500 text-[10px] mt-0.5 block">{{ $message }}</span> @enderror
                            </div>

                            <div>
                                <label class="block font-bold text-zinc-700 mb-1">Wholesale Price (TZS)</label>
                                <input type="number" step="1000" wire:model="wholesale_price_tzs" class="w-full rounded-xl border border-zinc-200 px-3.5 py-2.5 text-xs focus:border-[#1e3a8a]">
                            </div>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label class="block font-bold text-zinc-700 mb-1">Initial Stock Count *</label>
                                <input type="number" wire:model="stock_quantity" class="w-full rounded-xl border border-zinc-200 px-3.5 py-2.5 text-xs focus:border-[#1e3a8a]">
                                @error('stock_quantity') <span class="text-rose-500 text-[10px] mt-0.5 block">{{ $message }}</span> @enderror
                            </div>

                            <div>
                                <label class="block font-bold text-zinc-700 mb-1">Reorder Safety Threshold *</label>
                                <input type="number" wire:model="reorder_threshold" class="w-full rounded-xl border border-zinc-200 px-3.5 py-2.5 text-xs focus:border-[#1e3a8a]">
                                @error('reorder_threshold') <span class="text-rose-500 text-[10px] mt-0.5 block">{{ $message }}</span> @enderror
                            </div>
                        </div>

                        <div>
                            <label class="block font-bold text-zinc-700 mb-1">Product Photo URL</label>
                            <input type="url" wire:model="image_url" placeholder="https://..." class="w-full rounded-xl border border-zinc-200 px-3.5 py-2.5 text-xs focus:border-[#1e3a8a]">
                        </div>

                        <div class="pt-4 border-t border-zinc-100 flex items-center justify-end space-x-3">
                            <button 
                                type="button" 
                                wire:click="$set('showProductModal', false)" 
                                class="px-5 py-2.5 text-xs font-semibold text-zinc-600 hover:bg-zinc-100 rounded-xl transition cursor-pointer btn-interactive"
                            >
                                Cancel
                            </button>
                            <button 
                                type="submit" 
                                class="px-6 py-2.5 text-xs font-bold text-white bg-[#0a192f] hover:bg-[#1e3a8a] rounded-xl shadow-xs transition cursor-pointer btn-interactive"
                            >
                                {{ $isEditing ? 'Update Tyre SKU' : 'Save To Inventory' }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        @endif

        {{-- QUICK STOCK INTAKE MODAL --}}
        @if ($showAdjustModal)
            <div class="fixed inset-0 bg-black/60 backdrop-blur-xs z-50 flex items-center justify-center p-4 animate-fade-in">
                <div class="bg-white rounded-2xl max-w-md w-full p-6 space-y-4 shadow-2xl border border-zinc-100 animate-scale-up">
                    <div class="flex items-center justify-between pb-3 border-b border-zinc-100">
                        <h4 class="font-extrabold text-base text-zinc-950">Record Container Stock Intake</h4>
                        <button type="button" wire:click="closeAdjustModal" class="text-zinc-400 hover:text-zinc-700 p-1 rounded-lg hover:bg-zinc-100 btn-interactive cursor-pointer">&times;</button>
                    </div>

                    <div class="space-y-3 text-xs">
                        <p class="text-zinc-500">Enter the number of tyres received in the latest shipment batch to add directly into store balance.</p>
                        <div>
                            <label class="block font-bold text-zinc-700 mb-1">Quantity to Add (Tyres) *</label>
                            <input type="number" min="1" wire:model.live="adjustmentQuantity" class="w-full rounded-xl border border-zinc-200 px-3.5 py-2.5 text-xs font-bold text-[#0a192f] focus:border-[#1e3a8a] focus:ring-0">
                        </div>
                    </div>

                    <div class="flex items-center justify-end space-x-2 pt-3 border-t border-zinc-100">
                        <button type="button" wire:click="closeAdjustModal" class="px-4 py-2 text-xs font-semibold text-zinc-600 rounded-xl hover:bg-zinc-100 btn-interactive cursor-pointer">Cancel</button>
                        <button type="button" wire:click="applyStockIntake" class="px-5 py-2 text-xs font-bold text-white bg-[#0a192f] hover:bg-[#1e3a8a] rounded-xl shadow-xs btn-interactive cursor-pointer">Confirm Intake</button>
                    </div>
                </div>
            </div>
        @endif

        {{-- DELETE CONFIRMATION MODAL --}}
        @if ($showDeleteModal)
            <div class="fixed inset-0 bg-black/60 backdrop-blur-xs z-50 flex items-center justify-center p-4">
                <div class="bg-white rounded-2xl max-w-md w-full p-6 shadow-2xl border border-zinc-200 animate-in fade-in zoom-in duration-150">
                    <div class="w-12 h-12 rounded-full bg-rose-50 text-rose-600 flex items-center justify-center mx-auto mb-4 border border-rose-100">
                        <x-lucide name="alert-triangle" class="w-6 h-6" />
                    </div>

                    <h3 class="text-center text-lg font-bold text-zinc-950">Confirm Product Deletion</h3>
                    <p class="text-center text-xs text-zinc-500 mt-2 leading-relaxed">
                        Are you sure you want to permanently delete <strong class="text-zinc-800">{{ $deletingProductName }}</strong> from Kariakoo depot stock? This action cannot be undone.
                    </p>

                    <div class="mt-6 flex items-center justify-center space-x-3">
                        <button 
                            type="button" 
                            wire:click="cancelDelete" 
                            class="px-4 py-2 text-xs font-semibold text-zinc-700 bg-zinc-100 hover:bg-zinc-200 rounded-xl transition cursor-pointer"
                        >
                            Cancel
                        </button>
                        <button 
                            type="button" 
                            wire:click="deleteProduct" 
                            class="px-5 py-2 text-xs font-bold text-white bg-rose-600 hover:bg-rose-700 rounded-xl transition cursor-pointer shadow-xs"
                        >
                            Yes, Delete Product
                        </button>
                    </div>
                </div>
            </div>
        @endif
    </main>

    <x-page-footer />
</div>
