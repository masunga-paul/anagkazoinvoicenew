<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Print Invoice {{ $invoice->invoice_number }}</title>
    <link rel="icon" type="image/png" href="{{ asset('images/logo.png') }}">
    <link rel="shortcut icon" type="image/png" href="{{ asset('images/logo.png') }}">
    <link rel="apple-touch-icon" href="{{ asset('images/logo.png') }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Google+Sans+Flex:opsz,wght@6..144,1..1000&family=Inter:ital,opsz,wght@0,14..32,100..900;1,14..32,100..900&family=Pinyon+Script&family=Caveat:wght@600;700&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css'])
    <style>
        @page {
            size: A4 portrait;
            margin: 12mm 14mm 12mm 14mm;
        }
        @media print {
            html, body {
                background: #ffffff !important;
                color: #000000 !important;
                margin: 0 !important;
                padding: 0 !important;
                width: 100% !important;
                max-width: 100% !important;
                font-size: 11px !important;
                line-height: 1.35 !important;
                -webkit-print-color-adjust: exact !important;
                print-color-adjust: exact !important;
            }
            .no-print {
                display: none !important;
            }
            .page-container {
                padding: 0 !important;
                margin: 0 !important;
                box-shadow: none !important;
                border: none !important;
                max-width: 100% !important;
                width: 100% !important;
            }
            .avoid-page-break {
                break-inside: avoid !important;
                page-break-inside: avoid !important;
            }
            .print-table tr {
                break-inside: avoid !important;
                page-break-inside: avoid !important;
            }
            .watermark-print {
                position: fixed !important;
                top: 35% !important;
                left: 18% !important;
                width: 64% !important;
                opacity: 0.045 !important;
                z-index: -100 !important;
                text-align: center !important;
                pointer-events: none !important;
            }
        }
    </style>
</head>
<body class="bg-white text-zinc-900 font-sans p-6 sm:p-8 max-w-4xl mx-auto relative" onload="window.print()">

    {{-- Top Action Toolbar for Screen View --}}
    <div class="no-print mb-6 p-4 bg-zinc-100 rounded-xl flex items-center justify-between shadow-2xs">
        <div class="flex items-center gap-2">
            <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
            <span class="text-xs font-bold text-zinc-800">Print Preview • {{ $invoice->invoice_number }}</span>
        </div>
        <div class="flex items-center gap-2">
            <button onclick="window.print()" class="inline-flex items-center px-4 py-2 bg-[#0a192f] hover:bg-[#1e3a8a] text-white rounded-lg text-xs font-bold transition shadow-xs cursor-pointer">
                <svg class="w-3.5 h-3.5 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
                Print / Save PDF
            </button>
            <a 
                href="{{ route('invoices.show', $invoice) }}" 
                id="close-print-btn"
                onclick="if(window.opener && !window.opener.closed){ window.close(); return false; } else if(window.history.length > 1){ window.history.back(); return false; }"
                class="inline-flex items-center px-4 py-2 bg-white border border-zinc-300 hover:bg-zinc-100 text-zinc-700 rounded-lg text-xs font-semibold shadow-xs transition cursor-pointer"
            >
                <svg class="w-3.5 h-3.5 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                Close & Return
            </a>
        </div>
    </div>

    {{-- Watermark (Fixed on every printed page & absolute on screen) --}}
    <div class="watermark-print fixed top-[32%] left-[18%] w-[64%] text-center opacity-[0.045] pointer-events-none select-none z-0">
        <img src="{{ asset('images/logo.png') }}" alt="" class="w-full max-w-[380px] mx-auto grayscale">
    </div>

    <div class="page-container relative z-10">
        {{-- Invoice Paper Header --}}
        <div class="avoid-page-break flex items-start justify-between pb-4 border-b-2 border-zinc-900">
            <div class="flex items-start gap-3.5">
                <img src="{{ asset('images/logo.png') }}" alt="Anagkazo Autoparts" class="h-13 w-auto object-contain">
                <div>
                    <h1 class="text-xl font-black tracking-tight text-zinc-950 leading-tight">ANAGKAZO AUTOPARTS</h1>
                    <p class="text-[10px] text-zinc-600 mt-0.5 leading-snug">
                        Kariakoo Wholesale & Commercial Tyre Distributors<br>
                        Plot 42, Msimbazi & Uhuru Street, Dar es Salaam, Tanzania<br>
                        <strong>TIN:</strong> 188-458-408 | <strong></strong>  | <strong>Tel:</strong> +255 655 552 040
                    </p>
                    @if($invoice->issuer_name || $invoice->issuer_phone)
                        <div class="text-[10px] text-zinc-700 mt-0.5 font-medium">
                            <span class="text-zinc-500">Issued by:</span>
                            @if($invoice->issuer_name)
                                <span class="font-bold text-zinc-950">{{ $invoice->issuer_name }}</span>
                            @endif
                            @if($invoice->issuer_name && $invoice->issuer_phone)
                                <span> | </span>
                            @endif
                            @if($invoice->issuer_phone)
                                <span>Tel: {{ $invoice->issuer_phone }}</span>
                            @endif
                        </div>
                    @endif
                </div>
            </div>

            <div class="text-right">
                @if($invoice->tax_type === 'exclusive')
                    <div class="inline-block border-2 border-amber-700 bg-amber-50 text-amber-950 font-black px-2 py-0.5 text-[10px] uppercase tracking-wider mb-0.5">
                        TAX EXCLUSIVE INVOICE
                    </div>
                @else
                    <h2 class="text-lg font-bold uppercase tracking-wider text-zinc-900">TAX INVOICE</h2>
                @endif
                <div class="text-sm font-mono font-bold text-zinc-950">{{ $invoice->invoice_number }}</div>
                <div class="text-[10px] font-semibold text-zinc-500 uppercase mt-0.5">Status: <span class="text-zinc-900 font-bold">{{ $invoice->status }}</span></div>
            </div>
        </div>

        {{-- Metadata 4-Column Bar --}}
        <div class="avoid-page-break grid grid-cols-4 gap-3 py-2 px-3 bg-zinc-50 border border-zinc-200 rounded-lg text-[10px] my-3">
            <div>
                <span class="text-zinc-500 uppercase text-[8.5px] font-bold block">Issue Date</span>
                <span class="font-semibold text-zinc-950">{{ $invoice->issue_date ? $invoice->issue_date->format('d/m/Y') : '-' }}</span>
            </div>
            <div>
                <span class="text-zinc-500 uppercase text-[8.5px] font-bold block">Payment Due</span>
                <span class="font-semibold text-zinc-950">{{ $invoice->due_date ? $invoice->due_date->format('d/m/Y') : '-' }}</span>
            </div>
            <div>
                <span class="text-zinc-500 uppercase text-[8.5px] font-bold block">Payment Terms</span>
                <span class="font-semibold text-zinc-950">{{ $invoice->payment_terms ?: 'Cash on Delivery' }}</span>
            </div>
            <div>
                <span class="text-zinc-500 uppercase text-[8.5px] font-bold block">Recorded Time</span>
                <span class="font-semibold font-mono text-[10px] text-zinc-950">{{ $invoice->created_at ? $invoice->created_at->format('d/m/Y H:i') : '-' }}</span>
            </div>
        </div>

        {{-- Well-Arranged Side-by-Side Address Grid --}}
        <div class="avoid-page-break grid grid-cols-2 gap-4 py-2.5 border-b border-zinc-200 text-[10.5px]">
            <div class="p-2.5 bg-white border border-zinc-200 rounded-lg">
                <span class="text-zinc-500 uppercase text-[8.5px] font-bold tracking-wider block border-b border-zinc-100 pb-1 mb-1">Billed By (Supplier / Distributor):</span>
                <span class="font-bold text-xs text-zinc-950 block">Anagkazo Autoparts Ltd</span>
                <div class="text-zinc-600 space-y-0.5 mt-0.5 leading-snug text-[10px]">
                    <p>Plot 42, Msimbazi & Uhuru Street, Kariakoo</p>
                    <p>P.O. Box 24901, Dar es Salaam, Tanzania</p>
                    <p class="font-mono text-[10px]"><strong>TIN:</strong> 188-458-408 | <strong></strong> </p>
                    <p><strong>Tel:</strong> +255 655 552 040 | <strong>Email:</strong> sales@anagkazo.co.tz</p>
                </div>
            </div>

            <div class="p-2.5 bg-white border border-zinc-200 rounded-lg">
                <span class="text-zinc-500 uppercase text-[8.5px] font-bold tracking-wider block border-b border-zinc-100 pb-1 mb-1">Billed To (Customer / Client):</span>
                <div class="flex items-center gap-1.5 flex-wrap mb-0.5">
                    <span class="font-bold text-xs text-zinc-950">{{ $invoice->customer_name ?: ($invoice->customer?->name ?? 'Valued Customer') }}</span>
                    @if($invoice->customer_tier === 'premium')
                        <span class="inline-flex items-center px-1.5 py-0.2 rounded text-[8px] font-black bg-amber-100 text-amber-950 border border-amber-300">
                            ★ PREMIUM
                        </span>
                    @elseif($invoice->customer_tier === 'medium')
                        <span class="inline-flex items-center px-1.5 py-0.2 rounded text-[8px] font-bold bg-blue-50 text-blue-900 border border-blue-200">
                            MEDIUM
                        </span>
                    @endif
                </div>
                <div class="text-zinc-600 whitespace-pre-line space-y-0.5 leading-snug text-[10px]">
                    {!! nl2br(e($invoice->billing_address)) !!}
                    @if($invoice->customer?->phone && !str_contains($invoice->billing_address, $invoice->customer->phone))
                        <p class="mt-0.5"><strong>Phone:</strong> {{ $invoice->customer->phone }}</p>
                    @endif
                    @if($invoice->customer?->tin_number && !str_contains($invoice->billing_address, $invoice->customer->tin_number))
                        <p><strong>TIN:</strong> {{ $invoice->customer->tin_number }}</p>
                    @endif
                </div>
            </div>
        </div>

        {{-- Line Items --}}
        <div class="py-3">
            <table class="w-full text-[10.5px] print-table">
                <thead>
                    <tr class="border-b-2 border-zinc-900 text-[10px] font-bold uppercase text-zinc-900">
                        <th class="text-left py-1.5">Item Description</th>
                        <th class="text-center py-1.5">QTY</th>
                        <th class="text-right py-1.5">Unit Price (TZS)</th>
                        <th class="text-right py-1.5">Total (TZS)</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-zinc-200">
                    @foreach ($invoice->items as $item)
                        <tr class="avoid-page-break">
                            <td class="py-2 font-medium text-zinc-900">
                                {{ $item->item_description }}
                            </td>
                            <td class="py-2 text-center text-zinc-700 font-mono">
                                {{ $item->quantity }} {{ $item->unit_label }}
                            </td>
                            <td class="py-2 text-right text-zinc-700 font-mono">
                                {{ number_format($item->unit_price_tzs) }}
                            </td>
                            <td class="py-2 text-right font-bold font-mono text-zinc-950">
                                {{ number_format($item->total_price_tzs) }}
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        {{-- Totals & Bank details with Logos --}}
        <div class="avoid-page-break grid grid-cols-2 gap-6 pt-3 border-t-2 border-zinc-900 text-[10.5px]">
            <div class="text-[10px] text-zinc-700 space-y-1.5">
                <span class="font-bold block uppercase text-zinc-900 text-[10.5px]">Official Settlement Channels:</span>
                @php
                    $paymentMethodsList = $invoice->payment_methods_list;
                @endphp
                <div class="space-y-1.5">
                    @forelse($paymentMethodsList as $pm)
                        <div class="flex items-center gap-2">
                            @if($pm->logo_url)
                                <div class="w-6 h-6 rounded bg-white border border-zinc-300 p-0.5 flex items-center justify-center shrink-0">
                                    <img src="{{ $pm->logo_url }}" alt="{{ $pm->name }}" class="max-h-full max-w-full object-contain">
                                </div>
                            @endif
                            <div class="leading-tight">
                                <strong class="text-zinc-950">{{ $pm->name }}</strong>: <span class="font-mono">{{ $pm->type === 'mobile_money' ? 'Till ' : 'A/C ' }}{{ $pm->account_number_or_till }}</span>
                            </div>
                        </div>
                    @empty
                        <div>CRDB Bank (Kariakoo): <strong>0150294827100</strong></div>
                        <div>NMB Bank (Clock Tower): <strong>20810039210</strong></div>
                        <div>M-Pesa Lipa Namba (Till): <strong>5829104</strong> (Anagkazo Tyres)</div>
                    @endforelse
                </div>
            </div>

            <div class="space-y-1 text-right text-[10.5px]">
                <div class="flex justify-between text-zinc-600">
                    <span>Subtotal:</span>
                    <span class="font-mono">TZS {{ number_format($invoice->subtotal_tzs) }}</span>
                </div>
                @if($invoice->discount_tzs > 0)
                    <div class="flex justify-between text-zinc-600">
                        <span>Discount:</span>
                        <span class="font-mono">-TZS {{ number_format($invoice->discount_tzs) }}</span>
                    </div>
                @endif
                <div class="flex justify-between text-zinc-600">
                    @if($invoice->tax_type === 'inclusive')
                        <span>TAX ({{ (float) $invoice->tax_rate_percent }}%):</span>
                        <span class="font-mono font-bold text-zinc-900">+TZS {{ number_format($invoice->tax_amount_tzs) }}</span>
                    @else
                        <span class="font-bold text-amber-950">TAX Exclusive (0%):</span>
                        <span class="font-mono font-bold text-zinc-900">TZS 0</span>
                    @endif
                </div>
                <div class="flex justify-between text-zinc-950 font-black text-xs pt-1.5 border-t border-zinc-900">
                    <span>Grand Total:</span>
                    <span class="font-mono">TZS {{ number_format($invoice->total_amount_tzs) }}</span>
                </div>
            </div>
        </div>

        {{-- Disclaimer & Managing Director Signature --}}
        <div class="avoid-page-break mt-6 pt-3 border-t border-zinc-300 text-[10px] text-zinc-600 flex justify-between items-end">
            <div class="max-w-sm space-y-0.5">
                <span class="font-bold text-zinc-900 block">Terms & Conditions:</span>
                <p class="leading-snug text-zinc-600 text-[9.5px]">
                    1. Goods once inspected and delivered are non-refundable after 3 days.<br>
                    2. Official EFD receipt issued upon complete settlement.<br>
                    3. Thank you for choosing Anagkazo Tyres Kariakoo.
                </p>
            </div>

            <div class="relative text-right flex flex-col items-end min-w-[160px]">
                <span class="text-[8.5px] text-zinc-400 font-semibold uppercase tracking-wider block mb-0.5 z-10">Approved & Authorized by:</span>
                <div class="relative flex items-center justify-center my-0.5">
                    <img 
                        src="{{ asset('images/official-stamp.png') }}" 
                        alt="Anagkazo Official Stamp" 
                        class="w-24 h-24 object-contain opacity-90 absolute -top-5 pointer-events-none transform -rotate-6 select-none"
                    >
                    <div class="font-pinyon-script text-base text-zinc-950 font-normal leading-none py-1 select-none z-10">
                        Joseph Matemba
                    </div>
                </div>
                <span class="text-[9px] text-zinc-600 font-semibold uppercase tracking-wider block z-10">Managing Director</span>
            </div>
        </div>
    </div>
</body>
</html>
