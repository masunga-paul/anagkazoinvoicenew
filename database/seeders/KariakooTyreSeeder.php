<?php

namespace Database\Seeders;

use App\Models\Customer;
use App\Models\Invoice;
use App\Models\InvoiceItem;
use App\Models\PaymentMethod;
use App\Models\TyreProduct;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class KariakooTyreSeeder extends Seeder
{
    public function run(): void
    {
        // If operational product inventory already exists, DO NOT override it!
        if (TyreProduct::count() > 0) {
            return;
        }

        // 1. Seed Initial Kariakoo Tyre Products (Blank Database Only)
        $products = [
            [
                'sku' => 'TYR-TRI-31580225',
                'brand' => 'Triangle',
                'pattern' => 'TR668 Heavy Commercial Radial',
                'size' => '315/80R22.5',
                'category' => 'truck_bus_radial',
                'unit_price_tzs' => 750000,
                'wholesale_price_tzs' => 710000,
                'stock_quantity' => 140,
                'reorder_threshold' => 20,
                'image_url' => 'https://images.unsplash.com/photo-1578844251758-2f71da64c96f?w=600&auto=format&fit=crop&q=80',
            ],
            [
                'sku' => 'TYR-BRI-2656517',
                'brand' => 'Bridgestone',
                'pattern' => 'Dueler A/T 697 Rugged Terrain',
                'size' => '265/65R17',
                'category' => 'suv_4x4_all_terrain',
                'unit_price_tzs' => 480000,
                'wholesale_price_tzs' => 450000,
                'stock_quantity' => 45,
                'reorder_threshold' => 12,
                'image_url' => 'https://images.unsplash.com/photo-1549488344-1f9b8d2bd1f3?w=600&auto=format&fit=crop&q=80',
            ],
            [
                'sku' => 'TYR-MAX-2857017',
                'brand' => 'Maxxis',
                'pattern' => 'RAZR AT-811 Extreme Off-Road',
                'size' => '285/70R17',
                'category' => 'suv_4x4_all_terrain',
                'unit_price_tzs' => 560000,
                'wholesale_price_tzs' => 525000,
                'stock_quantity' => 28,
                'reorder_threshold' => 8,
                'image_url' => 'https://images.unsplash.com/photo-1613214149922-f1809c99b414?w=600&auto=format&fit=crop&q=80',
            ],
            [
                'sku' => 'TYR-DUN-2055516',
                'brand' => 'Dunlop',
                'pattern' => 'SP Sport LM705 Comfort Radial',
                'size' => '205/55R16',
                'category' => 'passenger_car',
                'unit_price_tzs' => 185000,
                'wholesale_price_tzs' => 170000,
                'stock_quantity' => 90,
                'reorder_threshold' => 15,
                'image_url' => 'https://images.unsplash.com/photo-1580273916550-e323be2ae537?w=600&auto=format&fit=crop&q=80',
            ],
            [
                'sku' => 'TYR-LIN-1200R20',
                'brand' => 'Linglong',
                'pattern' => 'D905 Highway Hauler Tube-Type',
                'size' => '12.00R20',
                'category' => 'truck_bus_radial',
                'unit_price_tzs' => 690000,
                'wholesale_price_tzs' => 640000,
                'stock_quantity' => 8, // low stock alert
                'reorder_threshold' => 15,
                'image_url' => 'https://images.unsplash.com/photo-1543785734-4b6e564642f8?w=600&auto=format&fit=crop&q=80',
            ],
            [
                'sku' => 'TYR-PIR-2254517',
                'brand' => 'Pirelli',
                'pattern' => 'Cinturato P7 Luxury Performance',
                'size' => '225/45R17',
                'category' => 'passenger_car',
                'unit_price_tzs' => 290000,
                'wholesale_price_tzs' => 270000,
                'stock_quantity' => 6, // low stock alert
                'reorder_threshold' => 10,
                'image_url' => 'https://images.unsplash.com/photo-1580273916550-e323be2ae537?w=600&auto=format&fit=crop&q=80',
            ],
        ];

        foreach ($products as $p) {
            TyreProduct::firstOrCreate(['sku' => $p['sku']], $p);
        }

        // 2. Seed Kariakoo Customers
        $customers = [
            [
                'name' => 'Mangi Auto Garage & Logistics Ltd',
                'contact_person' => 'Aloyce Mangi',
                'phone' => '+255 655 552 040',
                'email' => 'mangi.garage@gmail.com',
                'tin_number' => '104-892-334',
                'vrn_number' => '40-019283-K',
                'billing_address' => "Plot 58, Swahili & Msimbazi Street\nKariakoo, Ilala, Dar es Salaam",
                'customer_type' => 'corporate_ngo',
            ],
            [
                'name' => 'Bakhresa Express Transporters',
                'contact_person' => 'Salim Said',
                'phone' => '+255 689 332 110',
                'email' => 'logistics.fleet@bakhresa.com',
                'tin_number' => '100-291-884',
                'vrn_number' => '40-008129-A',
                'billing_address' => "Gerezani Industrial Strip, Bandari Road\nDar es Salaam, Tanzania",
                'customer_type' => 'corporate_ngo',
            ],
            [
                'name' => 'Kariakoo City Cab Drivers Association',
                'contact_person' => 'Juma Athumani',
                'phone' => '+255 713 445 678',
                'email' => 'kariakoocabs@yahoo.com',
                'tin_number' => '139-441-209',
                'vrn_number' => null,
                'billing_address' => "Uhuru Street, Stand ya Mabasi Karume\nKariakoo, Dar es Salaam",
                'customer_type' => 'retail',
            ],
            [
                'name' => 'Safari Coach Intercity Express',
                'contact_person' => 'Geoffrey Makala',
                'phone' => '+255 784 100 234',
                'email' => 'maintenance@safaricoach.co.tz',
                'tin_number' => '118-726-551',
                'vrn_number' => '40-033918-B',
                'billing_address' => "Magomeni / Morogoro Road Depot\nDar es Salaam, Tanzania",
                'customer_type' => 'corporate_ngo',
            ],
            [
                'name' => 'Tanzania Ports Authority (TPA) Transport Depot',
                'contact_person' => 'Eng. Baraka Mwita',
                'phone' => '+255 222 110 401',
                'email' => 'procurement@tpa.go.tz',
                'tin_number' => '100-001-998',
                'vrn_number' => '40-000192-G',
                'billing_address' => "Bandari Tower, Kurasini Wharf Area\nDar es Salaam, Tanzania",
                'customer_type' => 'government',
            ],
        ];

        foreach ($customers as $c) {
            Customer::firstOrCreate(['name' => $c['name']], $c);
        }

        // 3. Seed Existing Invoices
        $c1 = Customer::where('name', 'like', '%Mangi%')->first();
        $c2 = Customer::where('name', 'like', '%Bakhresa%')->first();
        $p1 = TyreProduct::where('sku', 'TYR-TRI-31580225')->first();
        $p2 = TyreProduct::where('sku', 'TYR-BRI-2656517')->first();

        if ($c1 && $p1 && $p2) {
            $inv1 = Invoice::firstOrCreate(
                ['invoice_number' => 'INV-DSM-2026-0001'],
                [
                    'customer_id' => $c1->id,
                    'customer_name' => $c1->name,
                    'billing_address' => $c1->billing_address,
                    'issuer_name' => 'Hussein Mwamba',
                    'issuer_phone' => '+255 655 552 040',
                    'issue_date' => now()->subDays(5)->format('Y-m-d'),
                    'due_date' => now()->addDays(9)->format('Y-m-d'),
                    'payment_terms' => '14 Days',
                    'status' => 'paid',
                    'subtotal_tzs' => 9420000,
                    'discount_tzs' => 200000,
                    'tax_rate_percent' => 18.00,
                    'tax_amount_tzs' => 1659600,
                    'total_amount_tzs' => 10879600,
                    'amount_paid_tzs' => 10879600,
                    'payment_method' => 'CRDB Bank (Kariakoo)',
                    'notes' => 'Thank you for your business. Payment confirmed via CRDB Account 0150294827100.',
                ]
            );

            InvoiceItem::firstOrCreate([
                'invoice_id' => $inv1->id,
                'tyre_product_id' => $p1->id,
                'item_description' => "{$p1->brand} {$p1->size} {$p1->pattern}",
                'unit_label' => 'tyres',
                'quantity' => 10,
                'unit_price_tzs' => 750000,
                'total_price_tzs' => 7500000,
            ]);

            InvoiceItem::firstOrCreate([
                'invoice_id' => $inv1->id,
                'tyre_product_id' => $p2->id,
                'item_description' => "{$p2->brand} {$p2->size} {$p2->pattern}",
                'unit_label' => 'tyres',
                'quantity' => 4,
                'unit_price_tzs' => 480000,
                'total_price_tzs' => 1920000,
            ]);
        }

        if ($c2 && $p1) {
            $inv2 = Invoice::firstOrCreate(
                ['invoice_number' => 'INV-DSM-2026-0002'],
                [
                    'customer_id' => $c2->id,
                    'customer_name' => $c2->name,
                    'billing_address' => $c2->billing_address,
                    'issuer_name' => 'Aloyce Mangi (Manager)',
                    'issuer_phone' => '+255 713 000 111',
                    'issue_date' => now()->subDays(2)->format('Y-m-d'),
                    'due_date' => now()->addDays(12)->format('Y-m-d'),
                    'payment_terms' => '14 Days',
                    'status' => 'issued',
                    'subtotal_tzs' => 15000000,
                    'discount_tzs' => 500000,
                    'tax_rate_percent' => 18.00,
                    'tax_amount_tzs' => 2610000,
                    'total_amount_tzs' => 17110000,
                    'amount_paid_tzs' => 5000000,
                    'payment_method' => 'M-Pesa Lipa Namba 5829104',
                    'notes' => 'Thank you for your business. Advance payment of TZS 5,000,000 received.',
                ]
            );

            InvoiceItem::firstOrCreate([
                'invoice_id' => $inv2->id,
                'tyre_product_id' => $p1->id,
                'item_description' => "{$p1->brand} {$p1->size} {$p1->pattern}",
                'unit_label' => 'tyres',
                'quantity' => 20,
                'unit_price_tzs' => 750000,
                'total_price_tzs' => 15000000,
            ]);
        }

        $c3 = Customer::where('name', 'like', '%Safari Coach%')->first();
        $c4 = Customer::where('name', 'like', '%Kariakoo City Cab%')->first();
        $p3 = TyreProduct::where('sku', 'TYR-MAX-2055516')->first() ?: $p2;

        if ($c3 && $p1) {
            $inv3 = Invoice::firstOrCreate(
                ['invoice_number' => 'INV-DSM-2026-0003'],
                [
                    'customer_id' => $c3->id,
                    'customer_name' => $c3->name,
                    'billing_address' => $c3->billing_address,
                    'issuer_name' => 'Joseph Matemba (Managing Director)',
                    'issuer_phone' => '+255 655 552 040',
                    'issue_date' => now()->subDays(25)->format('Y-m-d'),
                    'due_date' => now()->subDays(11)->format('Y-m-d'),
                    'payment_terms' => '14 Days',
                    'status' => 'issued',
                    'subtotal_tzs' => 6000000,
                    'discount_tzs' => 0,
                    'tax_rate_percent' => 0.00,
                    'tax_amount_tzs' => 0,
                    'total_amount_tzs' => 6000000,
                    'amount_paid_tzs' => 0,
                    'payment_method' => 'CRDB Bank (Kariakoo)',
                    'notes' => 'Please settle this overdue invoice at your earliest convenience.',
                ]
            );

            InvoiceItem::firstOrCreate([
                'invoice_id' => $inv3->id,
                'tyre_product_id' => $p1->id,
                'item_description' => "{$p1->brand} {$p1->size} {$p1->pattern}",
                'unit_label' => 'tyres',
                'quantity' => 8,
                'unit_price_tzs' => 750000,
                'total_price_tzs' => 6000000,
            ]);
        }

        if ($c4 && $p2) {
            $inv4 = Invoice::firstOrCreate(
                ['invoice_number' => 'INV-DSM-2026-0004'],
                [
                    'customer_id' => $c4->id,
                    'customer_name' => $c4->name,
                    'billing_address' => $c4->billing_address,
                    'issuer_name' => 'Aloyce Mangi (Manager)',
                    'issuer_phone' => '+255 713 000 111',
                    'issue_date' => now()->subDays(8)->format('Y-m-d'),
                    'due_date' => now()->subDays(1)->format('Y-m-d'),
                    'payment_terms' => '7 Days',
                    'status' => 'paid',
                    'subtotal_tzs' => 1920000,
                    'discount_tzs' => 0,
                    'tax_rate_percent' => 0.00,
                    'tax_amount_tzs' => 0,
                    'total_amount_tzs' => 1920000,
                    'amount_paid_tzs' => 1920000,
                    'payment_method' => 'Vodacom M-Pesa (Till 5829104)',
                    'notes' => 'Thank you for doing business with Anagkazo Tyres.',
                ]
            );

            InvoiceItem::firstOrCreate([
                'invoice_id' => $inv4->id,
                'tyre_product_id' => $p2->id,
                'item_description' => "{$p2->brand} {$p2->size} {$p2->pattern}",
                'unit_label' => 'tyres',
                'quantity' => 4,
                'unit_price_tzs' => 480000,
                'total_price_tzs' => 1920000,
            ]);
        }

        $c5 = Customer::where('name', 'like', '%Tanzania Ports Authority%')->first();
        if ($c5 && $p1) {
            $inv5 = Invoice::firstOrCreate(
                ['invoice_number' => 'INV-DSM-2026-0005'],
                [
                    'customer_id' => $c5->id,
                    'customer_name' => $c5->name,
                    'billing_address' => $c5->billing_address,
                    'issuer_name' => 'Joseph Matemba (MD)',
                    'issuer_phone' => '+255 655 552 040',
                    'issue_date' => now()->subDays(1)->format('Y-m-d'),
                    'due_date' => now()->addDays(29)->format('Y-m-d'),
                    'payment_terms' => '30 Days',
                    'status' => 'issued',
                    'subtotal_tzs' => 105000000,
                    'discount_tzs' => 0,
                    'tax_rate_percent' => 0.00,
                    'tax_amount_tzs' => 0,
                    'total_amount_tzs' => 105000000,
                    'amount_paid_tzs' => 60000000,
                    'payment_method' => 'CRDB Bank (Kariakoo Branch)',
                    'notes' => 'Tender Delivery Phase 1 — Heavy Port Container Hauler TBR Tyres.',
                ]
            );

            InvoiceItem::firstOrCreate([
                'invoice_id' => $inv5->id,
                'tyre_product_id' => $p1->id,
                'item_description' => "{$p1->brand} {$p1->size} {$p1->pattern}",
                'unit_label' => 'tyres',
                'quantity' => 140,
                'unit_price_tzs' => 750000,
                'total_price_tzs' => 105000000,
            ]);
        }

        // 4. Seed Kariakoo Payment Methods
        $paymentMethods = [
            [
                'name' => 'CRDB Bank (Kariakoo Branch)',
                'type' => 'bank_transfer',
                'bank_name' => 'CRDB Bank Plc',
                'account_number_or_till' => '0150294827100',
                'account_name' => 'Anagkazo Tyres Ltd',
                'branch' => 'Kariakoo Main Branch',
                'logo_url' => 'https://images.unsplash.com/photo-1628348068343-c6a848d2b6dd?w=200&auto=format&fit=crop&q=80',
                'instructions' => 'Use invoice number as payment reference',
                'is_active' => true,
                'is_default' => true,
            ],
            [
                'name' => 'Vodacom M-Pesa (Lipa Namba)',
                'type' => 'mobile_money',
                'bank_name' => 'Vodacom M-Pesa',
                'account_number_or_till' => '5829104',
                'account_name' => 'Anagkazo Tyres (Till)',
                'branch' => 'Dar es Salaam',
                'logo_url' => 'https://images.unsplash.com/photo-1563986768609-322da13575f3?w=200&auto=format&fit=crop&q=80',
                'instructions' => 'Dial *150*00# and select Pay Merchant',
                'is_active' => true,
                'is_default' => true,
            ],
            [
                'name' => 'NMB Bank (Clock Tower Branch)',
                'type' => 'bank_transfer',
                'bank_name' => 'NMB Bank Plc',
                'account_number_or_till' => '20810039210',
                'account_name' => 'Anagkazo Tyres Ltd',
                'branch' => 'Clock Tower Branch, DSM',
                'logo_url' => 'https://images.unsplash.com/photo-1559526324-4b87b5e36e44?w=200&auto=format&fit=crop&q=80',
                'instructions' => 'Please provide bank deposit slip or confirmation after payment',
                'is_active' => true,
                'is_default' => false,
            ],
            [
                'name' => 'Airtel Money (Merchant Pay)',
                'type' => 'mobile_money',
                'bank_name' => 'Airtel Tanzania',
                'account_number_or_till' => '948102',
                'account_name' => 'Anagkazo Tyres Depot',
                'branch' => 'Kariakoo Depot',
                'logo_url' => 'https://images.unsplash.com/photo-1563986768494-4dee2763ff3f?w=200&auto=format&fit=crop&q=80',
                'instructions' => 'Dial *150*60# and select Pay Bill',
                'is_active' => true,
                'is_default' => false,
            ],
        ];

        foreach ($paymentMethods as $pm) {
            PaymentMethod::firstOrCreate(
                ['account_number_or_till' => $pm['account_number_or_till']],
                $pm
            );
        }
    }
}
