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
        Schema::create('service_purchase_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('service_purchase_id')->constrained()->cascadeOnDelete();
            $table->string('service_name');
            $table->double('price');
            $table->double('quantity');
            $table->double('tax_rate');
            $table->double('total');
            $table->boolean('status')->default(true);
            $table->timestamps();
            $table->integer('allocation_id')->nullable();
            $table->integer('truck_cost_id')->nullable();
            $table->integer('allocation_cost_id')->nullable();
            $table->integer('retirement_id')->nullable();
            $table->double('discount_amount')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('service_purchase_items');
    }
};