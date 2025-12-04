<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('order_id')->unique();
            $table->decimal('total_amount', 15, 2);
            $table->string('promo_code')->nullable();
            $table->decimal('discount_amount', 15, 2)->default(0);
            $table->string('snap_token')->nullable();
            $table->enum('payment_method', ['midtrans', 'cod'])->default('midtrans')->comment('Metode pembayaran: midtrans atau cod');
            // $table->string('payment_type')->nullable();
            // $table->string('payment_status')->default('pending'); // pending, success, failed
            $table->string('status')->default('pending'); // pending, success
            $table->text('shipping_address')->nullable();
            $table->string('latitude')->nullable();
            $table->string('longitude')->nullable();
            $table->decimal('distance_from_store', 10, 2)->nullable()->comment('Jarak dari toko dalam meter');
            $table->integer('shipping_cost')->default(0)->comment('Biaya ongkir');
            $table->text('notes')->nullable();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};
