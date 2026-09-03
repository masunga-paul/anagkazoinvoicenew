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
        if (Schema::hasColumn('customers', 'credit_limit_tzs')) {
            Schema::table('customers', function (Blueprint $table) {
                $table->dropColumn('credit_limit_tzs');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (! Schema::hasColumn('customers', 'credit_limit_tzs')) {
            Schema::table('customers', function (Blueprint $table) {
                $table->decimal('credit_limit_tzs', 14, 2)->default(15000000.00)->after('customer_type');
            });
        }
    }
};
