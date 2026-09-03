<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('payment_methods', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // e.g. "CRDB Bank (Kariakoo Branch)"
            $table->enum('type', ['bank_transfer', 'mobile_money', 'cash', 'cheque'])->default('bank_transfer');
            $table->string('bank_name')->nullable(); // "CRDB Bank", "M-Pesa", "NMB Bank"
            $table->string('account_number_or_till'); // "0150294827100" or "5829104"
            $table->string('account_name')->default('Anagkazo Tyres Ltd');
            $table->string('branch')->nullable(); // "Kariakoo Branch"
            $table->text('instructions')->nullable();
            $table->boolean('is_active')->default(true);
            $table->boolean('is_default')->default(false);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('payment_methods');
    }
};
