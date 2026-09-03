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
        Schema::create('invoice_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('invoice_id')->constrained()->cascadeOnDelete();
            $table->foreignId('tyre_product_id')->nullable()->constrained()->nullOnDelete();
            $table->string('item_description');
            $table->string('unit_label')->default('tyres'); // tyres, pcs, sets
            $table->integer('quantity')->default(1);
            $table->decimal('unit_price_tzs', 14, 2);
            $table->decimal('total_price_tzs', 14, 2);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('invoice_items');
    }
};
