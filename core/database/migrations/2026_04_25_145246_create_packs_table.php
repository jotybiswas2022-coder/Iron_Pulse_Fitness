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
        Schema::create('packs', function (Blueprint $table) {
            $table->id();

            $table->string('name');
            $table->unsignedBigInteger('category_id');
            $table->enum('type', ['basic', 'standard', 'premium']);

            $table->decimal('total_cost', 10, 2);
            $table->decimal('pack_price', 10, 2);

            $table->integer('discount')->default(0);

            $table->text('details')->nullable();
            $table->string('image')->nullable();

            $table->timestamps();

            // Foreign Key
            $table->foreign('category_id')
                  ->references('id')
                  ->on('categories')
                  ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('packs');
    }
};