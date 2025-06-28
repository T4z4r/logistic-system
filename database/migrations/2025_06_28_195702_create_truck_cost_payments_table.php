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
        Schema::create('truck_cost_payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('truck_id')->constrained()->cascadeOnDelete();
            $table->foreignId('cost_id')->constrained('truck_costs')->cascadeOnDelete(); // assuming table name
            $table->foreignId('paid_by')->constrained('users')->cascadeOnDelete();
            $table->date('paid_date');
            $table->double('amount');
            $table->integer('remain')->default(0);
            $table->double('transaction_charges')->nullable();
            $table->double('service_charges')->nullable();
            $table->double('vat_charges')->nullable();
            $table->double('paid_amount')->nullable();
            $table->double('real_paid_amount')->nullable();
            $table->integer('payment_status')->default(1);
            $table->integer('transfer')->default(0);
            $table->text('reason')->nullable();
            $table->string('station')->nullable();
            $table->integer('currency_log_id')->default(1);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('truck_cost_payments');
    }
};