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
    Schema::create('payment_methods', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name');
            $table->bigInteger('ledger_id');
            $table->bigInteger('created_by');
            $table->integer('status')->default(0);
            $table->timestamps();
            $table->integer('currency_id')->default(0);
            $table->string('account_number_usd', 110)->nullable();
            $table->string('account_number_tzs', 255)->nullable();
            $table->string('branch_name')->nullable();
            $table->string('bank_name')->nullable();
            $table->string('swift_code')->nullable();
            $table->string('branch_code')->nullable();
            $table->index(['ledger_id', 'created_by']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payment_methods');
    }
};
