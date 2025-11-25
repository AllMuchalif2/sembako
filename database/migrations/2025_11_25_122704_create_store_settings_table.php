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
        Schema::create('store_settings', function (Blueprint $table) {
            $table->id();
            $table->string('store_name')->default('Toko Sembako');
            $table->decimal('store_latitude', 10, 8)->default(-6.200000);
            $table->decimal('store_longitude', 11, 8)->default(106.816666);
            $table->integer('free_shipping_radius')->default(10000)->comment('Radius gratis ongkir dalam meter');
            $table->integer('max_delivery_distance')->default(50000)->comment('Jarak maksimal pengiriman dalam meter');
            $table->integer('shipping_cost')->default(5000)->comment('Biaya ongkir di luar zona gratis');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('store_settings');
    }
};
