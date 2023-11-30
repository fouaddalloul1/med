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
        Schema::create('medicines', function (Blueprint $table) {
            $table->id();
            //names
            $table->string("scientific_en");
            $table->string("scientific_ar");
            $table->string("trade_en");
            $table->string("trade_ar");
            $table->string('image');
            $table->bigInteger("quantity");
            $table->float("price");
            $table->string("endDate");
            //if delete or update company or category the same thing will be medicine
            $table->foreignId("company_id")->references("id")->on('companies')->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreignId("category_id")->constrained("categories","id")->cascadeOnDelete()->cascadeOnUpdate();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('medicines');
    }
};
