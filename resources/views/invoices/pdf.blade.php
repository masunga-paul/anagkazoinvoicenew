<!DOCTYPE html>
<html lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Invoice {{ $invoice->invoice_number }}</title>
    <style>
        @page {
            margin: 22px 28px;
            size: A4 portrait;
        }
        body {
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
            font-size: 11px;
            line-height: 1.4;
            color: #1e293b;
            background-color: #ffffff;
            margin: 0;
            padding: 0;
        }
        .header-table {
            width: 100%;
            border-bottom: 2px solid #0a192f;
            padding-bottom: 12px;
            margin-bottom: 14px;
        }
        .logo-img {
            height: 52px;
            width: auto;
        }
        .company-title {
            font-size: 17px;
            font-weight: 900;
            color: #0a192f;
            letter-spacing: -0.5px;
            margin: 0;
        }
        .company-sub {
            font-size: 9px;
            color: #64748b;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 0.8px;
            margin-top: 2px;
        }
        .invoice-type-tag {
            background-color: #0a192f;
            color: #ffffff;
            font-size: 9px;
            font-weight: bold;
            text-transform: uppercase;
            padding: 3px 8px;
            border-radius: 3px;
            display: inline-block;
            letter-spacing: 0.5px;
        }
        .invoice-type-tag-exclusive {
            background-color: #78350f;
            color: #ffffff;
            font-size: 9px;
            font-weight: bold;
            text-transform: uppercase;
            padding: 3px 8px;
            border-radius: 3px;
            display: inline-block;
            letter-spacing: 0.5px;
        }
        .invoice-number {
            font-size: 15px;
            font-weight: 800;
            color: #0f172a;
            margin-top: 4px;
            font-family: 'Courier New', Courier, monospace;
        }
        .meta-table {
            width: 100%;
            border-collapse: collapse;
            background-color: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 4px;
            margin-bottom: 14px;
        }
        .meta-table td {
            padding: 7px 10px;
            vertical-align: middle;
            font-size: 10px;
            border-right: 1px solid #e2e8f0;
        }
        .meta-table td:last-child {
            border-right: none;
        }
        .meta-label {
            font-size: 8px;
            text-transform: uppercase;
            color: #64748b;
            font-weight: bold;
            display: block;
            margin-bottom: 1px;
        }
        .meta-val {
            font-weight: 700;
            color: #0f172a;
        }

        /* Well Arranged Address Grid */
        .address-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 16px;
        }
        .address-box {
            width: 50%;
            vertical-align: top;
            padding: 10px 12px;
            background-color: #ffffff;
            border: 1px solid #e2e8f0;
            border-radius: 4px;
        }
        .address-box-title {
            font-size: 8.5px;
            font-weight: 800;
            color: #475569;
            text-transform: uppercase;
            letter-spacing: 0.6px;
            border-bottom: 1px solid #e2e8f0;
            padding-bottom: 4px;
            margin-bottom: 6px;
        }
        .address-name {
            font-size: 12px;
            font-weight: 800;
            color: #0a192f;
            margin-bottom: 4px;
        }
        .address-body {
            font-size: 9.5px;
            color: #334155;
            line-height: 1.45;
        }

        /* Items Table */
        .items-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 14px;
        }
        .items-table th {
            background-color: #0a192f;
            color: #ffffff;
            font-size: 9px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            padding: 6px 8px;
            text-align: left;
        }
        .items-table td {
            padding: 6px 8px;
            border-bottom: 1px solid #e2e8f0;
            font-size: 10px;
            vertical-align: middle;
        }
        .items-table tr:nth-child(even) td {
            background-color: #f8fafc;
        }
        .item-desc {
            font-weight: 700;
            color: #0f172a;
        }
        .text-right {
            text-align: right;
        }
        .text-center {
            text-align: center;
        }
        .font-mono {
            font-family: 'Courier New', Courier, monospace;
        }

        /* Settlement & Totals */
        .bottom-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 6px;
        }
        .payment-box {
            width: 55%;
            vertical-align: top;
            padding-right: 15px;
        }
        .payment-title {
            font-size: 9px;
            font-weight: 800;
            color: #0a192f;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 6px;
        }
        .payment-item {
            background-color: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 4px;
            padding: 4px 8px;
            margin-bottom: 4px;
            font-size: 9px;
            color: #334155;
        }
        .payment-item-name {
            font-weight: 700;
            color: #0f172a;
        }
        .totals-box {
            width: 45%;
            vertical-align: top;
        }
        .totals-table {
            width: 100%;
            border-collapse: collapse;
        }
        .totals-table td {
            padding: 3px 0;
            font-size: 10px;
        }
        .totals-label {
            color: #64748b;
        }
        .totals-val {
            text-align: right;
            font-weight: 600;
            color: #0f172a;
        }
        .grand-total-row td {
            border-top: 1.5px solid #0a192f;
            padding-top: 6px;
            font-size: 12px;
            font-weight: 900;
            color: #0a192f;
        }

        /* Footer & Stamp */
        .footer-table {
            width: 100%;
            border-top: 1px solid #cbd5e1;
            margin-top: 16px;
            padding-top: 10px;
        }
        .terms-box {
            width: 60%;
            vertical-align: top;
            font-size: 8.5px;
            color: #64748b;
            line-height: 1.35;
        }
        .sign-box {
            width: 40%;
            text-align: right;
            vertical-align: top;
            position: relative;
        }
        .stamp-img {
            position: absolute;
            right: 20px;
            top: -15px;
            width: 85px;
            height: auto;
            opacity: 0.85;
        }
        .sign-name {
            font-family: Georgia, 'Times New Roman', serif;
            font-style: italic;
            font-size: 14px;
            color: #0a192f;
            font-weight: bold;
            margin-bottom: 2px;
        }
        .sign-title {
            font-size: 8.5px;
            color: #64748b;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
    </style>
<body>

    @php
        $logoPath = public_path('images/logo.png');
        $logoBase64 = file_exists($logoPath) ? 'data:image/png;base64,' . base64_encode(file_get_contents($logoPath)) : null;
    @endphp

    {{-- Company Logo Watermark --}}
    @if($logoBase64)
        <div style="position: fixed; top: 32%; left: 15%; width: 70%; text-align: center; opacity: 0.05; z-index: -1000;">
            <img src="{{ $logoBase64 }}" style="width: 360px; height: auto;" alt="Watermark">
        </div>
    @endif

    {{-- Header with Logo and Company Identity --}}
    <table class="header-table" cellpadding="0" cellspacing="0">
        <tr>
            <td style="width: 60%; vertical-align: top;">
                <table cellpadding="0" cellspacing="0">
                    <tr>
                        @if($logoBase64)
                            <td style="padding-right: 10px; vertical-align: top;">
                                <img src="{{ $logoBase64 }}" class="logo-img" alt="Anagkazo Logo">
                            </td>
                        @endif
                        <td style="vertical-align: top;">
                            <h1 class="company-title">ANAGKAZO AUTOPARTS</h1>
                            <div class="company-sub">Kariakoo Tyre Wholesalers & Distributors</div>
                            <div style="font-size: 9px; color: #475569; margin-top: 3px;">
                                Plot 42, Msimbazi & Uhuru St, Kariakoo, Dar es Salaam
                            </div>
                        </td>
                    </tr>
                </table>
            </td>
            <td style="width: 40%; text-align: right; vertical-align: top;">
                @if($invoice->tax_type === 'exclusive')
                    <span class="invoice-type-tag-exclusive">TAX EXCLUSIVE INVOICE</span>
                @else
                    <span class="invoice-type-tag">OFFICIAL TAX INVOICE</span>
                @endif
                <div class="invoice-number">{{ $invoice->invoice_number }}</div>
                <div style="font-size: 9px; color: #64748b; text-transform: uppercase; font-weight: bold; margin-top: 2px;">
                    Status: <span style="color: {{ $invoice->status === 'paid' ? '#166534' : ($invoice->status === 'issued' ? '#1e40af' : '#64748b') }}">{{ strtoupper($invoice->status) }}</span>
                </div>
            </td>
        </tr>
    </table>

    {{-- Metadata 4-Column Bar --}}
    <table class="meta-table" cellpadding="0" cellspacing="0">
        <tr>
            <td>
                <span class="meta-label">Issue Date</span>
                <span class="meta-val">{{ $invoice->issue_date ? $invoice->issue_date->format('d/m/Y') : date('d/m/Y') }}</span>
            </td>
            <td>
                <span class="meta-label">Due Date</span>
                <span class="meta-val">{{ $invoice->due_date ? $invoice->due_date->format('d/m/Y') : date('d/m/Y') }}</span>
            </td>
            <td>
                <span class="meta-label">Payment Terms</span>
                <span class="meta-val">{{ $invoice->payment_terms ?: 'Cash on Delivery' }}</span>
            </td>
            <td>
                <span class="meta-label">Recorded Date</span>
                <span class="meta-val">{{ $invoice->created_at ? $invoice->created_at->format('d/m/Y H:i') : date('d/m/Y H:i') }}</span>
            </td>
        </tr>
    </table>

    {{-- Well-Arranged Side-by-Side Address Grid --}}
    <table class="address-table" cellpadding="0" cellspacing="0">
        <tr>
            {{-- Supplier / Billed By --}}
            <td class="address-box" style="padding-right: 14px;">
                <div class="address-box-title">Billed By (Supplier / Distributor):</div>
                <div class="address-name">Anagkazo Autoparts Ltd</div>
                <div class="address-body">
                    Plot 42, Msimbazi & Uhuru Street, Kariakoo<br>
                    P.O. Box 24901, Dar es Salaam, Tanzania<br>
                    <strong>TIN:</strong> 188-458-408 &nbsp;|&nbsp; <strong></strong> <br>
                    <strong>Tel:</strong> +255 655 552 040 &nbsp;|&nbsp; <strong>Email:</strong> sales@anagkazo.co.tz<br>
                    @if($invoice->issuer_name || $invoice->issuer_phone)
                        <strong>Issued by:</strong> {{ $invoice->issuer_name ?? 'Commercial Desk' }} {{ $invoice->issuer_phone ? '('.$invoice->issuer_phone.')' : '' }}
                    @endif
                </div>
            </td>

            <td style="width: 14px;"></td>

            {{-- Customer / Billed To --}}
            <td class="address-box">
                <div class="address-box-title">Billed To (Customer / Client):</div>
                <div class="address-name">{{ $invoice->customer_name ?: 'Valued Customer' }}</div>
                <div class="address-body">
                    {!! nl2br(e($invoice->billing_address)) !!}
                    @if($invoice->customer?->phone && !str_contains($invoice->billing_address, $invoice->customer->phone))
                        <br><strong>Contact Phone:</strong> {{ $invoice->customer->phone }}
                    @endif
                    @if($invoice->customer?->tin_number && !str_contains($invoice->billing_address, $invoice->customer->tin_number))
                        <br><strong>Customer TIN:</strong> {{ $invoice->customer->tin_number }}
                    @endif
                </div>
            </td>
        </tr>
    </table>

    {{-- Invoice Items Table --}}
    <table class="items-table" cellpadding="0" cellspacing="0">
        <thead>
            <tr>
                <th style="width: 6%; text-align: center;">#</th>
                <th style="width: 48%;">Item Description & Tyre Specifications</th>
                <th style="width: 10%; text-align: center;">Qty</th>
                <th style="width: 16%; text-align: right;">Unit Price (TZS)</th>
                <th style="width: 20%; text-align: right;">Total Amount (TZS)</th>
            </tr>
        </thead>
        <tbody>
            @forelse($invoice->items as $idx => $item)
                <tr>
                    <td class="text-center font-mono" style="color: #64748b;">{{ $idx + 1 }}</td>
                    <td>
                        <span class="item-desc">{{ $item->item_description }}</span>
                        @if($item->unit_label)
                            <span style="font-size: 8.5px; color: #64748b;">({{ $item->unit_label }})</span>
                        @endif
                    </td>
                    <td class="text-center font-mono font-bold">{{ $item->quantity }}</td>
                    <td class="text-right font-mono">{{ number_format($item->unit_price_tzs) }}</td>
                    <td class="text-right font-mono font-bold">{{ number_format($item->total_price_tzs) }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" class="text-center" style="padding: 12px; color: #64748b;">No line items attached.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    {{-- Bottom Section: Settlement Channels on Left, Totals Calculation on Right --}}
    <table class="bottom-table" cellpadding="0" cellspacing="0">
        <tr>
            {{-- Preserved Payment Methods Selected by User --}}
            <td class="payment-box">
                <div class="payment-title">Official Settlement Channels:</div>
                @php
                    $methods = $invoice->payment_methods_list;
                @endphp
                @forelse($methods as $pm)
                    <table cellpadding="0" cellspacing="0" style="width: 100%; margin-bottom: 5px; background-color: #f8fafc; border: 1px solid #e2e8f0; border-radius: 4px; padding: 4px 6px;">
                        <tr>
                            @if($pm->logo_url)
                                <td style="width: 30px; vertical-align: middle; padding-right: 6px;">
                                    <img src="{{ $pm->logo_url }}" style="height: 22px; width: 22px; object-fit: contain; border-radius: 3px; border: 1px solid #cbd5e1; background: #ffffff; padding: 1px;" alt="{{ $pm->name }}">
                                </td>
                            @endif
                            <td style="vertical-align: middle; font-size: 9px; color: #334155; line-height: 1.3;">
                                <strong style="color: #0f172a; font-size: 9.5px;">{{ $pm->name }}</strong><br>
                                <span class="font-mono" style="font-weight: bold; color: #0a192f;">{{ $pm->type === 'mobile_money' ? 'Till: ' : 'A/C: ' }}{{ $pm->account_number_or_till }}</span>
                                <span style="color: #64748b;">({{ $pm->account_name }})</span>
                            </td>
                        </tr>
                    </table>
                @empty
                    <div class="payment-item">
                        <span class="payment-item-name">CRDB Bank (Kariakoo Branch):</span>
                        <span class="font-mono"><strong>0150294827100</strong></span>
                    </div>
                    <div class="payment-item">
                        <span class="payment-item-name">M-Pesa Lipa Namba (Till):</span>
                        <span class="font-mono"><strong>5829104</strong></span> (Anagkazo Tyres)
                    </div>
                @endforelse
            </td>

            {{-- Financial Calculations --}}
            <td class="totals-box">
                <table class="totals-table" cellpadding="0" cellspacing="0">
                    <tr>
                        <td class="totals-label">Subtotal:</td>
                        <td class="totals-val font-mono">TZS {{ number_format($invoice->subtotal_tzs) }}</td>
                    </tr>
                    @if($invoice->discount_tzs > 0)
                        <tr>
                            <td class="totals-label">Discount:</td>
                            <td class="totals-val font-mono" style="color: #dc2626;">-TZS {{ number_format($invoice->discount_tzs) }}</td>
                        </tr>
                    @endif
                    <tr>
                        @if($invoice->tax_type === 'inclusive')
                            <td class="totals-label">TRA VAT ({{ (float)$invoice->tax_rate_percent }}% Included):</td>
                            <td class="totals-val font-mono">+TZS {{ number_format($invoice->tax_amount_tzs) }}</td>
                        @else
                            <td class="totals-label" style="font-weight: bold; color: #92400e;">TAX Exclusive (0%):</td>
                            <td class="totals-val font-mono" style="color: #92400e;">TZS 0</td>
                        @endif
                    </tr>
                    <tr class="grand-total-row">
                        <td>Grand Total:</td>
                        <td class="totals-val font-mono" style="font-size: 13px; font-weight: 900;">TZS {{ number_format($invoice->total_amount_tzs) }}</td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>

    {{-- Footer Terms and Official Authorization Stamp --}}
    <table class="footer-table" cellpadding="0" cellspacing="0">
        <tr>
            <td class="terms-box">
                <strong style="color: #0f172a; text-transform: uppercase; font-size: 8px;">Terms & Conditions:</strong><br>
                @if($invoice->notes)
                    {!! nl2br(e($invoice->notes)) !!}
                @else
                    1. Goods once sold and inspected are covered under manufacturer warranty.<br>
                    2. Official TRA EFD receipt is issued upon full payment clearance.<br>
                    3. Thank you for choosing Anagkazo Autoparts Kariakoo.
                @endif
            </td>
            <td class="sign-box">
                @php
                    $stampPath = public_path('images/official-stamp.png');
                    $stampBase64 = file_exists($stampPath) ? 'data:image/png;base64,' . base64_encode(file_get_contents($stampPath)) : null;
                @endphp
                <div style="font-size: 8px; color: #94a3b8; text-transform: uppercase; font-weight: bold; margin-bottom: 2px;">
                    Approved & Authorized:
                </div>
                @if($stampBase64)
                    <img src="{{ $stampBase64 }}" class="stamp-img" alt="Official Stamp">
                @endif
                <div class="sign-name">Joseph Matemba</div>
                <div class="sign-title">Managing Director</div>
            </td>
        </tr>
    </table>

</body>
</html>
