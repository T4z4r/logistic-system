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
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('ledger_id')->nullable()->constrained()->onDelete('set null');
            $table->decimal('amount', 18, 2);
            $table->string('description')->nullable();
            $table->integer('type');
            $table->integer('status')->default(0);
            $table->timestamps();
            $table->softDeletes();
            $table->string('process')->nullable();
            $table->bigInteger('process_id')->nullable();
            $table->foreignId('driver_id')->nullable()->constrained()->onDelete('set null');
            $table->bigInteger('created_by')->nullable();
            $table->string('category', 20)->default('General Expense');
            $table->integer('currency_id')->nullable();
            $table->double('rate')->default(1);
            $table->integer('currency_value')->default(1);
            $table->integer('currency_log_id')->default(1);
            $table->double('corridor_rate')->default(1);
            $table->double('corridor_amount')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};