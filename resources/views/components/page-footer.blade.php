<footer class="mt-16 border-t border-zinc-200 bg-white/80 backdrop-blur-md">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-8 mb-8">
            {{-- Col 1: Brand & Overview --}}
            <div class="space-y-3 md:col-span-1">
                <div class="flex items-center space-x-2.5">
                    <img src="{{ asset('images/logo.png') }}" alt="Anagkazo Autoparts" class="h-8 w-auto object-contain" />
                    <span class="font-extrabold text-sm tracking-tight text-[#0a192f]">ANAGKAZO</span>
                </div>
                <p class="text-xs text-zinc-500 leading-relaxed">
                    Kariakoo Central Depot ERP & Invoice Management System. Authorized distributor of premium heavy commercial (TBR), SUV 4x4, and passenger tyres.
                </p>
            </div>

            {{-- Col 2: Navigation Links --}}
            <div>
                <h4 class="text-xs font-bold uppercase tracking-wider text-zinc-900 mb-3">Billing & Sales</h4>
                <ul class="space-y-2 text-xs text-zinc-600">
                    <li>
                        <a href="{{ route('invoices.create') }}" wire:navigate class="hover:text-[#1e3a8a] transition flex items-center gap-1.5">
                            <x-lucide name="plus-circle" class="w-3.5 h-3.5 text-zinc-400" />
                            Create New Invoice
                        </a>
                    </li>
                    @if(auth()->user()?->isAdmin())
                        <li>
                            <a href="{{ route('invoices.index') }}" wire:navigate class="hover:text-[#1e3a8a] transition flex items-center gap-1.5">
                                <x-lucide name="file-text" class="w-3.5 h-3.5 text-zinc-400" />
                                Invoices Registry
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('reports.index') }}" wire:navigate class="hover:text-[#1e3a8a] transition flex items-center gap-1.5">
                                <x-lucide name="bar-chart-3" class="w-3.5 h-3.5 text-zinc-400" />
                                Financial Analytics & Reports
                            </a>
                        </li>
                    @endif
                </ul>
            </div>

            {{-- Col 3: Inventory & Clients --}}
            <div>
                <h4 class="text-xs font-bold uppercase tracking-wider text-zinc-900 mb-3">Operations</h4>
                <ul class="space-y-2 text-xs text-zinc-600">
                    @if(auth()->user()?->isAdmin())
                        <li>
                            <a href="{{ route('dashboard') }}" wire:navigate class="hover:text-[#1e3a8a] transition flex items-center gap-1.5">
                                <x-lucide name="layout-dashboard" class="w-3.5 h-3.5 text-zinc-400" />
                                Depot Dashboard
                            </a>
                        </li>
                    @endif
                    <li>
                        <a href="{{ route('products.index') }}" wire:navigate class="hover:text-[#1e3a8a] transition flex items-center gap-1.5">
                            <x-lucide name="boxes" class="w-3.5 h-3.5 text-zinc-400" />
                            Tyre Stock Manager
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('customers.index') }}" wire:navigate class="hover:text-[#1e3a8a] transition flex items-center gap-1.5">
                            <x-lucide name="users" class="w-3.5 h-3.5 text-zinc-400" />
                            Customer Directory & Tiers
                        </a>
                    </li>
                    @if(auth()->user()?->isAdmin())
                        <li>
                            <a href="{{ route('payment-methods.index') }}" wire:navigate class="hover:text-[#1e3a8a] transition flex items-center gap-1.5">
                                <x-lucide name="credit-card" class="w-3.5 h-3.5 text-zinc-400" />
                                Payment Channels & Till Accounts
                            </a>
                        </li>
                    @endif
                </ul>
            </div>

            {{-- Col 4: Location & Contact --}}
            <div>
                <h4 class="text-xs font-bold uppercase tracking-wider text-zinc-900 mb-3">Depot Location</h4>
                <div class="space-y-2 text-xs text-zinc-600">
                    <p class="flex items-start gap-1.5">
                        <x-lucide name="map-pin" class="w-3.5 h-3.5 text-zinc-400 mt-0.5 shrink-0" />
                        <span>Msimbazi & Sikukuu Street, Kariakoo Commercial Hub, Dar es Salaam, Tanzania</span>
                    </p>
                    <p class="flex items-center gap-1.5">
                        <x-lucide name="phone" class="w-3.5 h-3.5 text-zinc-400 shrink-0" />
                        <span class="font-mono">+255 754 889 912</span>
                    </p>
                    <p class="flex items-center gap-1.5">
                        <x-lucide name="mail" class="w-3.5 h-3.5 text-zinc-400 shrink-0" />
                        <span class="font-mono">sales@anagkazo.co.tz</span>
                    </p>
                </div>
            </div>
        </div>

        {{-- Bottom Bar --}}
        <div class="pt-6 border-t border-zinc-100 flex flex-col sm:flex-row items-center justify-between gap-3 text-xs text-zinc-400">
            <p>&copy; {{ date('Y') }} Anagkazo Autoparts Ltd. All rights reserved. Registered under TRA TIN & VRN standards.</p>
            <div class="flex items-center space-x-4">
                <a 
                    href="https://www.designhub.co.tz" 
                    target="_blank" 
                    rel="noopener noreferrer" 
                    class="inline-flex items-center gap-1.5 text-zinc-500 hover:text-[#1e3a8a] font-medium transition group"
                >
                    <span>Developed by</span>
                    <span class="font-bold text-[#0a192f] group-hover:text-[#1e3a8a] group-hover:underline">DesignHub</span>
                    <x-lucide name="external-link" class="w-3 h-3 text-zinc-400 group-hover:text-[#1e3a8a]" />
                </a>
            </div>
        </div>
    </div>
</footer>
