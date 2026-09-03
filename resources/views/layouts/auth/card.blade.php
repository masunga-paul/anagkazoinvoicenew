<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">
    <head>
        @include('partials.head')
    </head>
    <body class="min-h-screen bg-neutral-100 antialiased dark:bg-linear-to-b dark:from-neutral-950 dark:to-neutral-900">
        <div class="bg-muted flex min-h-svh flex-col items-center justify-center gap-6 p-6 md:p-10">
            <div class="flex w-full max-w-md flex-col gap-6">
                <a href="{{ route('home') }}" class="flex flex-col items-center gap-2.5 font-medium group" wire:navigate>
                    <img src="{{ asset('images/logo.png') }}" alt="Anagkazo Logo" class="h-20 sm:h-24 w-auto object-contain transition duration-200 group-hover:scale-105">
                    <div class="text-center">
                        <span class="font-black text-xl tracking-tight text-zinc-950 dark:text-white block">ANAGKAZO AUTOPARTS</span>
                        <span class="text-xs font-semibold text-zinc-400 uppercase tracking-widest block">Kariakoo Tyre Billing & ERP</span>
                    </div>
                    <span class="sr-only">{{ config('app.name', 'Laravel') }}</span>
                </a>

                <div class="flex flex-col gap-6">
                    <div class="rounded-xl border bg-white dark:bg-stone-950 dark:border-stone-800 text-stone-800 shadow-xs">
                        <div class="px-10 py-8">{{ $slot }}</div>
                    </div>
                </div>
            </div>
        </div>

        @persist('toast')
            <flux:toast.group>
                <flux:toast />
            </flux:toast.group>
        @endpersist

        @livewireScripts
        @fluxScripts
    </body>
</html>
