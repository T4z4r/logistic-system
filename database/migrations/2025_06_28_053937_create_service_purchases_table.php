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
        Schema::create('service_purchases', function (Blueprint $table) {
            $table->id();
            $table->foreignId('supplier_id')->nullable()->constrained()->nullOnDelete();
            $table->string('service_purchase_prefix')->nullable();
            $table->string('service_purchase_order_no');
            $table->string('subject')->nullable();
            $table->double('tax_amount')->nullable();
            $table->string('currency_symbol')->nullable();
            $table->string('currency_rate')->nullable();
            $table->double('subtotal')->nullable();
            $table->double('payment_amount')->nullable();
            $table->double('total')->nullable();
            $table->text('description')->nullable();
            $table->string('order_status')->nullable();
            $table->string('state')->default('0');
            $table->timestamp('service_purchase_date')->useCurrent()->useCurrentOnUpdate();
            $table->date('due_date')->nullable();
            $table->bigInteger('workshop_request_id')->nullable();
            $table->boolean(column: 'status')->default(true);
            $table->string('purchase_type')->default('workshop');
            $table->integer('payment_level_status')->default(0);
            $table->integer('procurement_level_status')->default(0);
            $table->foreignId('created_by');
            $table->timestamps();
            $table->boolean('tag')->default(0);
            $table->integer('offbudget_id')->nullable();
            $table->integer('admin_id')->nullable();
            $table->integer('retirement_id')->nullable();
            $table->double('transaction_charges')->nullable();
            $table->double('discount_amount')->nullable();
            $table->integer('old_status')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('service_purchases');
    }
};
