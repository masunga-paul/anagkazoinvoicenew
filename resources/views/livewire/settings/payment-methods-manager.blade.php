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
                    <a href="{{ route('dashboard') }}" wire:navigate class="whitespace-nowrap px-3 py-1.5 text-xs font-semibold rounded-xl transition text-zinc-600 hover:text-zinc-900 hover:bg-zinc-100">Dashboard</a>
                @endif
                <a href="{{ route('invoices.create') }}" wire:navigate class="whitespace-nowrap px-3 py-1.5 text-xs font-semibold rounded-xl transition text-zinc-600 hover:text-zinc-900 hover:bg-zinc-100">Invoice</a>
                @if(auth()->user()?->isAdmin())
                    <a href="{{ route('invoices.index') }}" wire:navigate class="whitespace-nowrap px-3 py-1.5 text-xs font-semibold rounded-xl transition text-zinc-600 hover:text-zinc-900 hover:bg-zinc-100">Records</a>
                @endif
                <a href="{{ route('products.index') }}" wire:navigate class="whitespace-nowrap px-3 py-1.5 text-xs font-semibold rounded-xl transition text-zinc-600 hover:text-zinc-900 hover:bg-zinc-100">Stocks</a>
                <a href="{{ route('customers.index') }}" wire:navigate class="whitespace-nowrap px-3 py-1.5 text-xs font-semibold rounded-xl transition text-zinc-600 hover:text-zinc-900 hover:bg-zinc-100">Customers</a>
                @if(auth()->user()?->isAdmin())
                    <a href="{{ route('reports.index') }}" wire:navigate class="whitespace-nowrap px-3 py-1.5 text-xs font-semibold rounded-xl transition text-zinc-600 hover:text-zinc-900 hover:bg-zinc-100">Reports</a>
                    <a href="{{ route('payment-methods.index') }}" wire:navigate class="whitespace-nowrap px-3 py-1.5 text-xs font-semibold rounded-xl transition bg-[#0a192f] text-white shadow-xs">Payment Channels</a>
                @endif
            </nav>
        </div>

        {{-- Right: Role & Actions --}}
        <div class="flex items-center space-x-2.5 shrink-0">
            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-bold bg-amber-100 text-amber-900 border border-amber-300 whitespace-nowrap">
                Admin
            </span>

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

    <main class="max-w-5xl mx-auto space-y-6">
        @if (session()->has('success'))
            <div class="p-4 rounded-xl bg-blue-50 border border-blue-200 text-blue-900 flex items-center gap-3">
                <x-lucide name="check-circle-2" class="w-5 h-5 text-blue-600 shrink-0" />
                <span class="text-sm font-medium">{{ session('success') }}</span>
            </div>
        @endif

        {{-- Top Info Header --}}
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <a href="{{ route('invoices.create') }}" class="inline-flex items-center text-xs font-medium text-zinc-500 hover:text-zinc-800 transition mb-1">
                    <x-lucide name="arrow-left" class="w-3.5 h-3.5 mr-1" />
                    Back to Invoice Builder
                </a>
                <h1 class="text-2xl sm:text-3xl font-extrabold tracking-tight text-zinc-950">Payments & Settlement</h1>
                <p class="text-xs text-zinc-500 mt-1">Configure bank accounts, M-Pesa Lipa Namba, and settlement instructions shown on customer invoices.</p>
            </div>

            <div>
                <button 
                    type="button" 
                    wire:click="$toggle('showForm')"
                    class="inline-flex items-center px-4 py-2 text-sm font-semibold text-white bg-[#0a192f] hover:bg-[#1e3a8a] rounded-xl shadow-xs transition"
                >
                    @if ($showForm)
                        <x-lucide name="x" class="w-4 h-4 mr-1.5 text-zinc-300" />
                        Close Form
                    @else
                        <x-lucide name="credit-card" class="w-4 h-4 mr-1.5 text-blue-400" />
                        New Payment Channel
                    @endif
                </button>
            </div>
        </div>

        {{-- Add Payment Method Collapsible Card --}}
        @if ($showForm)
            <div class="bg-white rounded-2xl p-6 sm:p-8 border border-blue-200 shadow-md transition space-y-6">
                <div class="flex items-center justify-between border-b border-zinc-100 pb-4">
                    <div class="flex items-center space-x-3">
                        <div class="w-9 h-9 rounded-xl bg-blue-50 text-[#1e3a8a] flex items-center justify-center">
                            <x-lucide name="wallet" class="w-5 h-5" />
                        </div>
                        <div>
                            <h3 class="font-bold text-base text-zinc-900">{{ $editingId ? 'Edit Payment' : 'Add New Payment' }}</h3>
                            <p class="text-xs text-zinc-500">This channel will immediately be available on newly generated Kariakoo invoices.</p>
                        </div>
                    </div>
                </div>

                <form wire:submit="saveMethod" novalidate class="space-y-4">
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-semibold text-zinc-700 mb-1">Display Name *</label>
                            <input 
                                type="text" 
                                wire:model="name"
                                placeholder="e.g. Stanbic Bank Kariakoo, Tigo Pesa Lipa Namba"
                                class="w-full text-xs rounded-xl border border-zinc-200 px-3 py-2.5 text-zinc-900 focus:border-[#1e3a8a] focus:ring-1 focus:ring-[#1e3a8a]"
                            />
                            @error('name') <span class="text-xs text-rose-500 mt-0.5 block">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-zinc-700 mb-1">Channel Type *</label>
                            <select 
                                wire:model="type"
                                class="w-full text-xs rounded-xl border border-zinc-200 px-3 py-2.5 text-zinc-900 bg-white focus:border-[#1e3a8a]"
                            >
                                <option value="bank_transfer">Bank Transfer (Akaunti ya Benki)</option>
                                <option value="mobile_money">Mobile Money (M-Pesa / Airtel / Tigo)</option>
                                <option value="cash">Cash on Delivery (Pesa Taslimu)</option>
                                <option value="cheque">Cheque (Hundi)</option>
                            </select>
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-zinc-700 mb-1">Bank / Institution Provider</label>
                            <input 
                                type="text" 
                                wire:model="bank_name"
                                placeholder="e.g. CRDB Bank, Vodacom Tanzania, NMB Bank"
                                class="w-full text-xs rounded-xl border border-zinc-200 px-3 py-2.5 text-zinc-900 focus:border-[#1e3a8a]"
                            />
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-zinc-700 mb-1">Account Number / Till Number *</label>
                            <input 
                                type="text" 
                                wire:model="account_number_or_till"
                                placeholder="e.g. 0150294827100 or Lipa Namba 5829104"
                                class="w-full text-xs font-mono font-bold rounded-xl border border-zinc-200 px-3 py-2.5 text-zinc-900 focus:border-[#1e3a8a]"
                            />
                            @error('account_number_or_till') <span class="text-xs text-rose-500 mt-0.5 block">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-zinc-700 mb-1">Account Beneficiary Name *</label>
                            <input 
                                type="text" 
                                wire:model="account_name"
                                placeholder="Anagkazo Tyres Ltd"
                                class="w-full text-xs rounded-xl border border-zinc-200 px-3 py-2.5 text-zinc-900 focus:border-[#1e3a8a]"
                            />
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-zinc-700 mb-1">Branch / Location</label>
                            <input 
                                type="text" 
                                wire:model="branch"
                                placeholder="Kariakoo, Msimbazi Street"
                                class="w-full text-xs rounded-xl border border-zinc-200 px-3 py-2.5 text-zinc-900 focus:border-[#1e3a8a]"
                            />
                        </div>

                        <div class="sm:col-span-2">
                            <label class="block text-xs font-semibold text-zinc-700 mb-1">Bank / Mobile Company Logo Image Link (URL)</label>
                            <div class="flex items-center gap-3">
                                <input 
                                    type="url" 
                                    wire:model.live.debounce.300ms="logo_url"
                                    placeholder="https://example.com/logo.png"
                                    class="flex-1 text-xs rounded-xl border border-zinc-200 px-3 py-2.5 text-zinc-900 focus:border-[#1e3a8a]"
                                />
                                @if($logo_url)
                                    <div class="w-10 h-10 rounded-xl bg-white border border-zinc-200 p-1 flex items-center justify-center shrink-0 shadow-2xs">
                                        <img src="{{ $logo_url }}" alt="Logo Preview" class="max-h-full max-w-full object-contain" onerror="this.style.display='none'">
                                    </div>
                                @endif
                            </div>
                            <p class="text-[10px] text-zinc-400 mt-1">Provide an image link for CRDB, NMB, M-Pesa, etc. to appear directly on printed and digital invoices.</p>
                            @error('logo_url') <span class="text-xs text-rose-500 mt-0.5 block">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-zinc-700 mb-1">Customer Instructions</label>
                        <textarea 
                            wire:model="instructions"
                            rows="2"
                            placeholder="e.g. Use invoice number as payment reference..."
                            class="w-full text-xs rounded-xl border border-zinc-200 px-3 py-2 text-zinc-900 focus:border-[#1e3a8a]"
                        ></textarea>
                    </div>

                    <div class="flex items-center justify-between pt-2">
                        <label class="flex items-center text-xs text-zinc-700 font-medium cursor-pointer">
                            <input type="checkbox" wire:model="is_default" class="rounded border-zinc-300 text-[#1e3a8a] focus:ring-[#1e3a8a] mr-2">
                            Set as Default Channel on Invoices
                        </label>

                        <div class="flex items-center space-x-3">
                            <button 
                                type="button" 
                                wire:click="resetForm"
                                class="px-4 py-2 text-xs font-semibold text-zinc-600 bg-zinc-100 hover:bg-zinc-200 rounded-xl transition"
                            >
                                Cancel
                            </button>
                            <button 
                                type="submit" 
                                class="px-5 py-2 text-xs font-bold text-white bg-[#0a192f] hover:bg-[#1e3a8a] rounded-xl shadow-xs transition"
                            >
                                Save Channel
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        @endif

        {{-- Existing Payment Methods Grid --}}
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            @foreach ($methods as $method)
                <div class="bg-white rounded-2xl border border-zinc-200/90 p-6 shadow-xs card-interactive flex flex-col justify-between space-y-4 relative overflow-hidden">
                    @if ($method->is_default)
                        <div class="absolute top-0 right-0 bg-[#1e3a8a] text-white text-[9px] font-bold px-3 py-1 rounded-bl-xl uppercase tracking-wider">
                            Default
                        </div>
                    @endif

                    <div class="space-y-2">
                        <div class="flex items-center space-x-3">
                            @if($method->logo_url)
                                <div class="w-12 h-12 rounded-xl bg-white border border-zinc-200 p-1 flex items-center justify-center shrink-0 shadow-2xs">
                                    <img src="{{ $method->logo_url }}" alt="{{ $method->name }}" class="max-h-full max-w-full object-contain">
                                </div>
                            @else
                                <div class="w-10 h-10 rounded-xl bg-blue-50 text-[#1e3a8a] flex items-center justify-center font-bold">
                                    @if($method->type === 'mobile_money')
                                        <x-lucide name="phone" class="w-5 h-5" />
                                    @else
                                        <x-lucide name="building-2" class="w-5 h-5" />
                                    @endif
                                </div>
                            @endif
                            <div>
                                <h3 class="font-bold text-sm text-zinc-950">{{ $method->name }}</h3>
                                <span class="text-[11px] text-zinc-400 capitalize">{{ str_replace('_', ' ', $method->type) }}</span>
                            </div>
                        </div>

                        <div class="bg-zinc-50 p-3.5 rounded-xl border border-zinc-100 space-y-1">
                            <div class="flex items-center justify-between text-xs">
                                <span class="text-zinc-500">Account / Till:</span>
                                <span class="font-mono font-bold text-zinc-950 text-sm">{{ $method->account_number_or_till }}</span>
                            </div>
                            <div class="flex items-center justify-between text-xs">
                                <span class="text-zinc-500">Beneficiary:</span>
                                <span class="font-medium text-zinc-800">{{ $method->account_name }}</span>
                            </div>
                            @if ($method->branch)
                                <div class="flex items-center justify-between text-xs">
                                    <span class="text-zinc-500">Branch:</span>
                                    <span class="text-zinc-700">{{ $method->branch }}</span>
                                </div>
                            @endif
                        </div>

                        @if ($method->instructions)
                            <p class="text-[11px] text-zinc-500 italic">
                                "{{ $method->instructions }}"
                            </p>
                        @endif
                    </div>

                    <div class="pt-3 border-t border-zinc-100 flex items-center justify-between">
                        <button 
                            type="button" 
                            wire:click="toggleStatus({{ $method->id }})" 
                            class="text-xs font-semibold {{ $method->is_active ? 'text-emerald-700' : 'text-zinc-400' }} hover:underline cursor-pointer"
                        >
                            {{ $method->is_active ? 'Active on Invoices' : 'Disabled' }}
                        </button>

                        <div class="flex items-center space-x-2">
                            <button 
                                type="button" 
                                wire:click="editMethod({{ $method->id }})" 
                                class="inline-flex items-center text-xs text-zinc-600 hover:text-[#0a192f] font-semibold px-2 py-1 hover:bg-zinc-100 rounded-lg transition cursor-pointer"
                                title="Edit Method"
                            >
                                <x-lucide name="pencil" class="w-3.5 h-3.5 mr-1 text-zinc-500" />
                                Edit
                            </button>

                            <button 
                                type="button" 
                                wire:click="confirmDelete({{ $method->id }})" 
                                class="inline-flex items-center text-xs text-rose-500 hover:text-rose-700 font-semibold px-2 py-1 hover:bg-rose-50 rounded-lg transition cursor-pointer"
                                title="Delete Method"
                            >
                                <x-lucide name="trash-2" class="w-3.5 h-3.5 mr-1" />
                                Delete
                            </button>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        {{-- DELETE CONFIRMATION MODAL --}}
        @if ($showDeleteModal)
            <div class="fixed inset-0 bg-black/60 backdrop-blur-xs z-50 flex items-center justify-center p-4 animate-fade-in">
                <div class="bg-white rounded-2xl max-w-md w-full p-6 shadow-2xl border border-zinc-200 animate-scale-up">
                    <div class="w-12 h-12 rounded-full bg-rose-50 text-rose-600 flex items-center justify-center mx-auto mb-4 border border-rose-100">
                        <x-lucide name="alert-triangle" class="w-6 h-6" />
                    </div>

                    <h3 class="text-center text-lg font-bold text-zinc-950">Confirm Deletion</h3>
                    <p class="text-center text-xs text-zinc-500 mt-2 leading-relaxed">
                        Are you sure you want to delete Payment <strong class="text-zinc-800">{{ $deletingMethodName }}</strong>? This channel will no longer appear as a payment option.
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
                            wire:click="deleteMethod" 
                            class="px-5 py-2 text-xs font-bold text-white bg-rose-600 hover:bg-rose-700 rounded-xl transition cursor-pointer shadow-xs btn-interactive"
                        >
                            Yes, Delete Channel
                        </button>
                    </div>
                </div>
            </div>
        @endif
    </main>

    <x-page-footer />
</div>
