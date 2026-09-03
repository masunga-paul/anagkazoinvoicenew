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
        Schema::create('tyre_products', function (Blueprint $table) {
            $table->id();
            $table->string('sku')->unique();
            $table->string('brand'); // Triangle, Bridgestone, Maxxis, Linglong, Dunlop, Pirelli
            $table->string('pattern'); // TR668, Dueler A/T, CrossContact, AT-771
            $table->string('size'); // 315/80R22.5, 205/55R16, 265/65R17
            $table->enum('category', ['truck_bus_radial', 'passenger_car', 'suv_4x4_all_terrain', 'industrial_agricultural']);
            $table->decimal('unit_price_tzs', 14, 2);
            $table->decimal('wholesale_price_tzs', 14, 2)->nullable();
            $table->integer('stock_quantity')->default(0);
            $table->integer('reorder_threshold')->default(10);
            $table->string('image_url')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tyre_products');
    }
};
