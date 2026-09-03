<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('customers', function (Blueprint $table) {
            $table->string('customer_type', 50)->default('retail')->change();
        });

        // Migrate existing records to new category values
        DB::table('customers')->where('customer_type', 'retail_walk_in')->update(['customer_type' => 'retail']);
        DB::table('customers')->where('customer_type', 'wholesale_garage')->update(['customer_type' => 'corporate_ngo']);
        DB::table('customers')->where('customer_type', 'fleet_transporter')->update(['customer_type' => 'corporate_ngo']);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('customers', function (Blueprint $table) {
            $table->string('customer_type', 50)->default('retail_walk_in')->change();
        });
    }
};
