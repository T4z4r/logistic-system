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
        Schema::create('trip_invoices', function (Blueprint $table) {
            $table->id();
            $table->string('invoice_ref');
            $table->foreignId('allocation_id')->constrained()->onDelete('cascade');
            $table->double('amount');
            $table->foreignId('currency_id')->constrained()->onDelete('cascade');
            $table->double('real_amount');
            $table->double('rate')->nullable();
            $table->date('start_date');
            $table->date('due_date');
            $table->integer('credit_term')->default(30);
            $table->string('vat')->default('No');
            $table->integer('status')->default(0);
            $table->string('state')->default('0');
            $table->foreignId('created_by')->constrained('users');
            $table->string('type')->nullable();
            $table->string('account')->nullable();
            $table->text('note')->nullable();
            $table->integer('currency_log_id')->default(1);
            $table->double('credit_amount')->default(0);
            $table->integer('payment_status')->default(1);
            $table->double('paid_amount')->default(0);
            $table->double('real_paid_amount')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('trip_invoices');
    }
};