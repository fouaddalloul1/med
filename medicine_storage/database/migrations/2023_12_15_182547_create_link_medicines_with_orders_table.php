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
        Schema::create('link_medicines_with_orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId("order_id")->references("id")->on('orders')->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreignId("medicine_id")->references("id")->on('medicines')->cascadeOnDelete()->cascadeOnUpdate();
            $table->string('quantityOfThisMedicine');
            //price of one piece from this medicine
            $table->string('singlePrice');
            //price of multi pieces from this medicine =
            // singlePrice in this column or price in medicines column * quantity
            $table->string('totalPrice');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('link_medicines_with_orders');
    }
};
