<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('invoices', function (Blueprint $table) {
            $table->id();
            $table->string('invoice_number')->unique(); // e.g. INV-DSM-2026-0001
            $table->foreignId('customer_id')->nullable()->constrained()->nullOnDelete();
            $table->string('customer_name')->nullable();
            $table->text('billing_address')->nullable();
            $table->date('issue_date');
            $table->date('due_date');
            $table->string('payment_terms')->default('Net 14');
            $table->enum('status', ['draft', 'issued', 'partially_paid', 'paid', 'cancelled'])->default('draft');
            $table->decimal('subtotal_tzs', 14, 2)->default(0);
            $table->decimal('discount_tzs', 14, 2)->default(0);
            $table->decimal('tax_rate_percent', 5, 2)->default(18.00); // TRA VAT 18%
            $table->decimal('tax_amount_tzs', 14, 2)->default(0);
            $table->decimal('total_amount_tzs', 14, 2)->default(0);
            $table->decimal('amount_paid_tzs', 14, 2)->default(0);
            $table->string('payment_method')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('invoices');
    }
};
