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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id');
            $table->string('firstname');
            $table->string('lastname');
            $table->string('email');
            $table->string('phone');
            $table->text('address');

            $table->decimal('pack_price_after_discount', 15, 2);
            $table->decimal('delivery_charge', 15, 2);
            $table->decimal('tax', 15, 2);
            $table->decimal('total_price', 15, 2);

            $table->string('status')->default('pending');
            $table->timestamp('approved_at')->nullable(); // 🔥 MERGED HERE

            $table->string('payment_method')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};