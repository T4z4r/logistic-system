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
        Schema::create('process_ledger_mappers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('process_id')->nullable()->constrained('process_ledgers')->onDelete('set null');
            $table->unsignedBigInteger('ledger_debit_id')->nullable();
            $table->unsignedBigInteger('ledger_credit_id')->nullable();
            $table->unsignedBigInteger('sub_account_debit_id')->nullable();
            $table->unsignedBigInteger('sub_account_credit_id')->nullable();
            $table->unsignedBigInteger('vat_id')->nullable();
            $table->decimal('vat_percentage', 8, 2)->nullable();
            $table->integer('status')->default(1);
            $table->integer('credit_level')->default(5);
            $table->integer('debit_level')->default(5);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('process_ledger_mappers');
    }
};
