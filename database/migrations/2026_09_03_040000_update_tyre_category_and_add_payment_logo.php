<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('tyre_products', function (Blueprint $table) {
            $table->string('category', 100)->default('Truck & Bus Radial (TBR)')->change();
        });

        Schema::table('payment_methods', function (Blueprint $table) {
            $table->string('logo_url')->nullable()->after('branch');
        });
    }

    public function down(): void
    {
        Schema::table('payment_methods', function (Blueprint $table) {
            $table->dropColumn('logo_url');
        });
    }
};
