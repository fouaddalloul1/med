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
            $table->boolean('preparing')->default(0);
            $table->boolean('sent')->default(0);
            $table->boolean('received')->default(0);
            $table->boolean('payed')->default(0);
            $table->integer('totalPrice');
            //link each order with user (id)
            $table->foreignId("users_id")->references("id")->on('users')->cascadeOnDelete()->cascadeOnUpdate();
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
