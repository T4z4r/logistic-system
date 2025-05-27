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
        Schema::create('allocation_cost_payments', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('allocation_id')->nullable();
            $table->unsignedBigInteger('truck_id');
            $table->unsignedBigInteger('cost_id');
            $table->unsignedBigInteger('paid_by');
            $table->date('paid_date');
            $table->double('amount');
            $table->integer('remain')->default(0);
            $table->timestamps();
            $table->double('transaction_charges')->nullable();
            $table->double('service_charges')->default(0);
            $table->double('vat_charges')->default(0);
            $table->integer('payment_status')->default(0);
            $table->integer('transfer')->default(0);
            $table->text('reason')->nullable();
            $table->string('station')->nullable();
            $table->double('paid_amount')->nullable();
            $table->double('real_paid_amount')->nullable();
            $table->integer('currency_log_id')->default(1);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('allocation_cost_payments');
    }
};
