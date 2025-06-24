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
        Schema::create('suppliers', function (Blueprint $table) {
            $table->id();
            $table->string('name', 125);
            $table->string('phone', 125);
            $table->string('email', 125);
            $table->string('company', 125)->nullable();
            $table->string('credit_term', 125)->nullable();
            $table->string('address', 125)->nullable();
            $table->string('tin_number', 125)->nullable();
            $table->string('vrn_number', 125)->nullable();
            $table->string('bank_name', 125)->nullable();
            $table->string('bank_account', 125)->nullable();
            $table->integer('user_type')->default(1);
            $table->boolean('status')->default(1);
            $table->foreignId('created_by');
            $table->timestamps();
            $table->integer('balance_ledger')->nullable();
            $table->foreignId('region_id')->nullable()->constrained('org_regions');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('suppliers');
    }
};
