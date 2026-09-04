<div class="min-h-screen bg-[#f3f4f6] text-zinc-900 py-6 px-4 sm:px-6 lg:px-8 font-sans antialiased">
    {{-- Global Top Navigation Bar with Navy Theme --}}
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
                    <a href="{{ route('payment-methods.index') }}" wire:navigate class="whitespace-nowrap px-3 py-1.5 text-xs font-semibold rounded-xl transition text-zinc-600 hover:text-zinc-900 hover:bg-zinc-100">Payment Channels</a>
                @endif
            </nav>
        </div>

        {{-- Right: Role & Actions --}}
        <div class="flex items-center space-x-2.5 shrink-0">
            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-bold bg-amber-100 text-amber-900 border border-amber-300 whitespace-nowrap">
                Admin
            </span>

            <a href="{{ route('dashboard') }}" class="inline-flex items-center whitespace-nowrap px-3.5 py-1.5 text-xs font-bold text-zinc-700 bg-white border border-zinc-200 hover:bg-zinc-50 rounded-xl shadow-xs transition">
                <x-lucide name="layout-dashboard" class="w-3.5 h-3.5 mr-1 text-zinc-500" />
                Dashboard
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

    <main class="max-w-7xl mx-auto space-y-8">
        {{-- Page Header --}}
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 bg-white p-6 rounded-3xl border border-zinc-200 shadow-xs">
            <div class="space-y-1">
                <div class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full bg-rose-50 border border-rose-200 text-rose-700 text-xs font-semibold">
                    <x-lucide name="shield-alert" class="w-3.5 h-3.5" />
                    Administrator Access Only
                </div>
                <h1 class="text-2xl font-black text-zinc-900 tracking-tight">Security Credentials & Access Control</h1>
                <p class="text-xs text-zinc-500">
                    Update administrator and staff authentication credentials. Once updated, you will be automatically logged out to enforce session renewal.
                </p>
            </div>

            <div class="flex items-center gap-2">
                <button 
                    type="button"
                    wire:click="$toggle('showNewStaffForm')"
                    class="inline-flex items-center px-4 py-2.5 rounded-xl bg-[#0a192f] hover:bg-[#1e3a8a] text-white text-xs font-bold transition shadow-xs cursor-pointer"
                >
                    <x-lucide name="user-plus" class="w-4 h-4 mr-1.5 text-blue-300" />
                    Add Staff Member
                </button>
            </div>
        </div>

        {{-- Success Notification --}}
        @if(session()->has('staff_success'))
            <div class="p-4 rounded-2xl bg-emerald-50 border border-emerald-200 text-emerald-800 text-xs font-semibold flex items-center gap-3 shadow-xs">
                <x-lucide name="check-circle-2" class="w-5 h-5 text-emerald-600 shrink-0" />
                <span>{{ session('staff_success') }}</span>
            </div>
        @endif

        {{-- 2-Column Grid: Admin Credentials on Left, Staff Credentials on Right --}}
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            
            {{-- 1. Admin Credentials Card --}}
            <div class="bg-white rounded-3xl border border-zinc-200 shadow-xs overflow-hidden flex flex-col">
                <div class="p-6 border-b border-zinc-100 bg-gradient-to-r from-slate-900 to-[#0a192f] text-white flex items-center justify-between">
                    <div class="flex items-center space-x-3">
                        <div class="p-2.5 rounded-2xl bg-white/10 border border-white/20">
                            <x-lucide name="shield-check" class="w-5 h-5 text-blue-300" />
                        </div>
                        <div>
                            <h2 class="text-base font-bold text-white">Administrator Credentials</h2>
                            <p class="text-[11px] text-blue-200">Main admin user with full access to records, stocks & settings</p>
                        </div>
                    </div>
                    <span class="px-2.5 py-1 rounded-full text-[10px] font-black uppercase tracking-wider bg-blue-500/30 text-blue-200 border border-blue-400/30">
                        Admin Role
                    </span>
                </div>

                <form wire:submit="updateAdminCredentials" class="p-6 space-y-5 flex-1 flex flex-col justify-between">
                    <div class="space-y-4">
                        {{-- Admin Name --}}
                        <div>
                            <label class="block text-xs font-bold text-zinc-700 uppercase tracking-wider mb-1.5">
                                Administrator Full Name
                            </label>
                            <input 
                                type="text" 
                                wire:model="admin_name" 
                                class="w-full text-xs px-4 py-2.5 rounded-xl border border-zinc-200 focus:border-[#1e3a8a] focus:ring-0 text-zinc-900 font-medium"
                                placeholder="e.g. Admin / Joseph Matemba"
                                required
                            />
                            @error('admin_name') <span class="text-rose-600 text-[11px] mt-1 block">{{ $message }}</span> @enderror
                        </div>

                        {{-- Admin Email --}}
                        <div>
                            <label class="block text-xs font-bold text-zinc-700 uppercase tracking-wider mb-1.5">
                                Administrator Login Email
                            </label>
                            <input 
                                type="email" 
                                wire:model="admin_email" 
                                class="w-full text-xs px-4 py-2.5 rounded-xl border border-zinc-200 focus:border-[#1e3a8a] focus:ring-0 text-zinc-900 font-medium"
                                placeholder="admin@anagkazo.co.tz"
                                required
                            />
                            @error('admin_email') <span class="text-rose-600 text-[11px] mt-1 block">{{ $message }}</span> @enderror
                        </div>

                        {{-- Admin New Password --}}
                        <div x-data="{ show: false }">
                            <label class="block text-xs font-bold text-zinc-700 uppercase tracking-wider mb-1.5">
                                New Password <span class="text-zinc-400 font-normal lowercase">(leave blank to keep current)</span>
                            </label>
                            <div class="relative">
                                <input 
                                    :type="show ? 'text' : 'password'" 
                                    wire:model="admin_password" 
                                    class="w-full text-xs px-4 py-2.5 pr-10 rounded-xl border border-zinc-200 focus:border-[#1e3a8a] focus:ring-0 text-zinc-900 font-medium font-mono"
                                    placeholder="••••••••••••"
                                />
                                <button 
                                    type="button" 
                                    @click="show = !show" 
                                    class="absolute inset-y-0 right-0 pr-3 flex items-center text-zinc-400 hover:text-zinc-700 transition cursor-pointer"
                                    tabindex="-1"
                                    :title="show ? 'Hide password' : 'View password'"
                                >
                                    <span x-show="!show" class="flex items-center">
                                        <x-lucide name="eye" class="w-4 h-4" />
                                    </span>
                                    <span x-show="show" x-cloak class="flex items-center text-blue-600">
                                        <x-lucide name="eye-off" class="w-4 h-4" />
                                    </span>
                                </button>
                            </div>
                            @error('admin_password') <span class="text-rose-600 text-[11px] mt-1 block">{{ $message }}</span> @enderror
                        </div>

                        {{-- Admin Confirm Password --}}
                        <div x-data="{ show: false }">
                            <label class="block text-xs font-bold text-zinc-700 uppercase tracking-wider mb-1.5">
                                Confirm New Password
                            </label>
                            <div class="relative">
                                <input 
                                    :type="show ? 'text' : 'password'" 
                                    wire:model="admin_password_confirmation" 
                                    class="w-full text-xs px-4 py-2.5 pr-10 rounded-xl border border-zinc-200 focus:border-[#1e3a8a] focus:ring-0 text-zinc-900 font-medium font-mono"
                                    placeholder="••••••••••••"
                                />
                                <button 
                                    type="button" 
                                    @click="show = !show" 
                                    class="absolute inset-y-0 right-0 pr-3 flex items-center text-zinc-400 hover:text-zinc-700 transition cursor-pointer"
                                    tabindex="-1"
                                    :title="show ? 'Hide password' : 'View password'"
                                >
                                    <span x-show="!show" class="flex items-center">
                                        <x-lucide name="eye" class="w-4 h-4" />
                                    </span>
                                    <span x-show="show" x-cloak class="flex items-center text-blue-600">
                                        <x-lucide name="eye-off" class="w-4 h-4" />
                                    </span>
                                </button>
                            </div>
                        </div>
                    </div>

                    <div class="pt-4 border-t border-zinc-100">
                        <button 
                            type="submit" 
                            class="w-full inline-flex items-center justify-center px-5 py-3 rounded-xl bg-[#0a192f] hover:bg-[#1e3a8a] text-white text-xs font-bold transition shadow-sm cursor-pointer"
                        >
                            <x-lucide name="lock" class="w-4 h-4 mr-2 text-blue-300" />
                            Update Admin Credentials & Log Out
                        </button>
                    </div>
                </form>
            </div>

            {{-- 2. Staff Credentials Card --}}
            <div class="bg-white rounded-3xl border border-zinc-200 shadow-xs overflow-hidden flex flex-col">
                <div class="p-6 border-b border-zinc-100 bg-gradient-to-r from-blue-900 to-[#1e3a8a] text-white flex items-center justify-between">
                    <div class="flex items-center space-x-3">
                        <div class="p-2.5 rounded-2xl bg-white/10 border border-white/20">
                            <x-lucide name="users" class="w-5 h-5 text-blue-200" />
                        </div>
                        <div>
                            <h2 class="text-base font-bold text-white">Staff Login Credentials</h2>
                            <p class="text-[11px] text-blue-200">Staff accounts can view stocks/customers & issue invoices</p>
                        </div>
                    </div>
                    <span class="px-2.5 py-1 rounded-full text-[10px] font-black uppercase tracking-wider bg-white/20 text-white border border-white/30">
                        Staff Role
                    </span>
                </div>

                <div class="p-6 space-y-5 flex-1 flex flex-col justify-between">
                    {{-- Staff Selector if multiple --}}
                    @if($staffMembers->count() > 1)
                        <div>
                            <label class="block text-xs font-bold text-zinc-700 uppercase tracking-wider mb-1.5">
                                Select Staff User Account to Modify
                            </label>
                            <div class="flex flex-wrap gap-2">
                                @foreach($staffMembers as $sm)
                                    <button 
                                        type="button" 
                                        wire:click="selectStaff({{ $sm->id }})"
                                        class="px-3 py-1.5 rounded-xl text-xs font-semibold border transition cursor-pointer {{ $selected_staff_id === $sm->id ? 'bg-[#0a192f] text-white border-[#0a192f]' : 'bg-zinc-50 text-zinc-700 border-zinc-200 hover:bg-zinc-100' }}"
                                    >
                                        {{ $sm->name }} ({{ $sm->email }})
                                    </button>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    <form wire:submit="updateStaffCredentials" class="space-y-4 flex-1 flex flex-col justify-between">
                        <div class="space-y-4">
                            {{-- Staff Name --}}
                            <div>
                                <label class="block text-xs font-bold text-zinc-700 uppercase tracking-wider mb-1.5">
                                    Staff Member Name
                                </label>
                                <input 
                                    type="text" 
                                    wire:model="staff_name" 
                                    class="w-full text-xs px-4 py-2.5 rounded-xl border border-zinc-200 focus:border-[#1e3a8a] focus:ring-0 text-zinc-900 font-medium"
                                    placeholder="e.g. Staff / Sales Desk"
                                    required
                                />
                                @error('staff_name') <span class="text-rose-600 text-[11px] mt-1 block">{{ $message }}</span> @enderror
                            </div>

                            {{-- Staff Email --}}
                            <div>
                                <label class="block text-xs font-bold text-zinc-700 uppercase tracking-wider mb-1.5">
                                    Staff Login Email
                                </label>
                                <input 
                                    type="email" 
                                    wire:model="staff_email" 
                                    class="w-full text-xs px-4 py-2.5 rounded-xl border border-zinc-200 focus:border-[#1e3a8a] focus:ring-0 text-zinc-900 font-medium"
                                    placeholder="staff@anagkazo.co.tz"
                                    required
                                />
                                @error('staff_email') <span class="text-rose-600 text-[11px] mt-1 block">{{ $message }}</span> @enderror
                            </div>

                            {{-- Staff New Password --}}
                            <div x-data="{ show: false }">
                                <label class="block text-xs font-bold text-zinc-700 uppercase tracking-wider mb-1.5">
                                    New Password <span class="text-zinc-400 font-normal lowercase">(leave blank to keep current)</span>
                                </label>
                                <div class="relative">
                                    <input 
                                        :type="show ? 'text' : 'password'" 
                                        wire:model="staff_password" 
                                        class="w-full text-xs px-4 py-2.5 pr-10 rounded-xl border border-zinc-200 focus:border-[#1e3a8a] focus:ring-0 text-zinc-900 font-medium font-mono"
                                        placeholder="••••••••••••"
                                    />
                                    <button 
                                        type="button" 
                                        @click="show = !show" 
                                        class="absolute inset-y-0 right-0 pr-3 flex items-center text-zinc-400 hover:text-zinc-700 transition cursor-pointer"
                                        tabindex="-1"
                                        :title="show ? 'Hide password' : 'View password'"
                                    >
                                        <span x-show="!show" class="flex items-center">
                                            <x-lucide name="eye" class="w-4 h-4" />
                                        </span>
                                        <span x-show="show" x-cloak class="flex items-center text-blue-600">
                                            <x-lucide name="eye-off" class="w-4 h-4" />
                                        </span>
                                    </button>
                                </div>
                                @error('staff_password') <span class="text-rose-600 text-[11px] mt-1 block">{{ $message }}</span> @enderror
                            </div>

                            {{-- Staff Confirm Password --}}
                            <div x-data="{ show: false }">
                                <label class="block text-xs font-bold text-zinc-700 uppercase tracking-wider mb-1.5">
                                    Confirm New Password
                                </label>
                                <div class="relative">
                                    <input 
                                        :type="show ? 'text' : 'password'" 
                                        wire:model="staff_password_confirmation" 
                                        class="w-full text-xs px-4 py-2.5 pr-10 rounded-xl border border-zinc-200 focus:border-[#1e3a8a] focus:ring-0 text-zinc-900 font-medium font-mono"
                                        placeholder="••••••••••••"
                                    />
                                    <button 
                                        type="button" 
                                        @click="show = !show" 
                                        class="absolute inset-y-0 right-0 pr-3 flex items-center text-zinc-400 hover:text-zinc-700 transition cursor-pointer"
                                        tabindex="-1"
                                        :title="show ? 'Hide password' : 'View password'"
                                    >
                                        <span x-show="!show" class="flex items-center">
                                            <x-lucide name="eye" class="w-4 h-4" />
                                        </span>
                                        <span x-show="show" x-cloak class="flex items-center text-blue-600">
                                            <x-lucide name="eye-off" class="w-4 h-4" />
                                        </span>
                                    </button>
                                </div>
                            </div>
                        </div>

                        <div class="pt-4 border-t border-zinc-100">
                            <button 
                                type="submit" 
                                class="w-full inline-flex items-center justify-center px-5 py-3 rounded-xl bg-[#1e3a8a] hover:bg-[#1d4ed8] text-white text-xs font-bold transition shadow-sm cursor-pointer"
                            >
                                <x-lucide name="key-round" class="w-4 h-4 mr-2 text-blue-200" />
                                Save Staff Credentials & Invalidate Staff Session
                            </button>
                        </div>
                    </form>
                </div>
            </div>

        </div>

        {{-- Add New Staff Modal / Slide-out --}}
        @if($showNewStaffForm)
            <div class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-zinc-900/60 backdrop-blur-xs">
                <div class="bg-white rounded-3xl border border-zinc-200 shadow-2xl max-w-lg w-full overflow-hidden">
                    <div class="p-6 border-b border-zinc-100 bg-[#0a192f] text-white flex items-center justify-between">
                        <div class="flex items-center space-x-3">
                            <x-lucide name="user-plus" class="w-5 h-5 text-blue-300" />
                            <h3 class="text-base font-bold">Register New Staff Account</h3>
                        </div>
                        <button 
                            type="button" 
                            wire:click="$toggle('showNewStaffForm')"
                            class="p-1 rounded-lg text-zinc-400 hover:text-white hover:bg-white/10 transition cursor-pointer"
                        >
                            <x-lucide name="x" class="w-5 h-5" />
                        </button>
                    </div>

                    <form wire:submit="createStaff" class="p-6 space-y-4">
                        <div>
                            <label class="block text-xs font-bold text-zinc-700 uppercase tracking-wider mb-1.5">
                                Staff Full Name
                            </label>
                            <input 
                                type="text" 
                                wire:model="new_staff_name" 
                                class="w-full text-xs px-4 py-2.5 rounded-xl border border-zinc-200 focus:border-[#1e3a8a] focus:ring-0 text-zinc-900"
                                placeholder="e.g. Sales Associate"
                                required
                            />
                            @error('new_staff_name') <span class="text-rose-600 text-[11px] mt-1 block">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-zinc-700 uppercase tracking-wider mb-1.5">
                                Staff Email
                            </label>
                            <input 
                                type="email" 
                                wire:model="new_staff_email" 
                                class="w-full text-xs px-4 py-2.5 rounded-xl border border-zinc-200 focus:border-[#1e3a8a] focus:ring-0 text-zinc-900"
                                placeholder="e.g. sales.kariakoo@anagkazo.co.tz"
                                required
                            />
                            @error('new_staff_email') <span class="text-rose-600 text-[11px] mt-1 block">{{ $message }}</span> @enderror
                        </div>

                        <div x-data="{ show: false }">
                            <label class="block text-xs font-bold text-zinc-700 uppercase tracking-wider mb-1.5">
                                Initial Password
                            </label>
                            <div class="relative">
                                <input 
                                    :type="show ? 'text' : 'password'" 
                                    wire:model="new_staff_password" 
                                    class="w-full text-xs px-4 py-2.5 pr-10 rounded-xl border border-zinc-200 focus:border-[#1e3a8a] focus:ring-0 text-zinc-900 font-mono"
                                    placeholder="••••••••••••"
                                    required
                                />
                                <button 
                                    type="button" 
                                    @click="show = !show" 
                                    class="absolute inset-y-0 right-0 pr-3 flex items-center text-zinc-400 hover:text-zinc-700 transition cursor-pointer"
                                    tabindex="-1"
                                    :title="show ? 'Hide password' : 'View password'"
                                >
                                    <span x-show="!show" class="flex items-center">
                                        <x-lucide name="eye" class="w-4 h-4" />
                                    </span>
                                    <span x-show="show" x-cloak class="flex items-center text-blue-600">
                                        <x-lucide name="eye-off" class="w-4 h-4" />
                                    </span>
                                </button>
                            </div>
                            @error('new_staff_password') <span class="text-rose-600 text-[11px] mt-1 block">{{ $message }}</span> @enderror
                        </div>

                        <div x-data="{ show: false }">
                            <label class="block text-xs font-bold text-zinc-700 uppercase tracking-wider mb-1.5">
                                Confirm Initial Password
                            </label>
                            <div class="relative">
                                <input 
                                    :type="show ? 'text' : 'password'" 
                                    wire:model="new_staff_password_confirmation" 
                                    class="w-full text-xs px-4 py-2.5 pr-10 rounded-xl border border-zinc-200 focus:border-[#1e3a8a] focus:ring-0 text-zinc-900 font-mono"
                                    placeholder="••••••••••••"
                                    required
                                />
                                <button 
                                    type="button" 
                                    @click="show = !show" 
                                    class="absolute inset-y-0 right-0 pr-3 flex items-center text-zinc-400 hover:text-zinc-700 transition cursor-pointer"
                                    tabindex="-1"
                                    :title="show ? 'Hide password' : 'View password'"
                                >
                                    <span x-show="!show" class="flex items-center">
                                        <x-lucide name="eye" class="w-4 h-4" />
                                    </span>
                                    <span x-show="show" x-cloak class="flex items-center text-blue-600">
                                        <x-lucide name="eye-off" class="w-4 h-4" />
                                    </span>
                                </button>
                            </div>
                        </div>

                        <div class="pt-4 border-t border-zinc-100 flex items-center justify-end gap-2">
                            <button 
                                type="button" 
                                wire:click="$toggle('showNewStaffForm')"
                                class="px-4 py-2 rounded-xl text-xs font-bold text-zinc-600 hover:bg-zinc-100 transition cursor-pointer"
                            >
                                Cancel
                            </button>
                            <button 
                                type="submit" 
                                class="px-5 py-2.5 rounded-xl bg-[#0a192f] hover:bg-[#1e3a8a] text-white text-xs font-bold transition shadow-xs cursor-pointer"
                            >
                                Create Staff Account
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        @endif
    </main>

    {{-- Footer with Developed by DesignHub --}}
    <footer class="max-w-7xl mx-auto mt-16 pt-8 border-t border-zinc-200 text-xs text-zinc-500 flex flex-col sm:flex-row items-center justify-between gap-4">
        <div class="flex items-center space-x-2">
            <span class="font-bold text-zinc-700">Anagkazo Autoparts Ltd</span>
            <span>&bull;</span>
            <span>Plot 42, Msimbazi & Uhuru St, Kariakoo, Dar es Salaam</span>
        </div>
        <div class="flex items-center space-x-4">
            <a href="https://www.designhub.co.tz" target="_blank" rel="noopener noreferrer" class="font-semibold text-blue-900 hover:text-blue-700 transition">
                Developed by DesignHub
            </a>
        </div>
    </footer>
</div>
