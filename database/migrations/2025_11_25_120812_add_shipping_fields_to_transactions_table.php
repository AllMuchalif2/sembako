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
        Schema::table('transactions', function (Blueprint $table) {
            $table->decimal('distance_from_store', 10, 2)->nullable()->after('longitude')->comment('Jarak dari toko dalam meter');
            $table->integer('shipping_cost')->default(0)->after('distance_from_store')->comment('Biaya ongkir');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('transactions', function (Blueprint $table) {
            $table->dropColumn(['distance_from_store', 'shipping_cost']);
        });
    }
};
