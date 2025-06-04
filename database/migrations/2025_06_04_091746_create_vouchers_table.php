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
        Schema::create('vouchers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->constrained()->onDelete('cascade');
            $table->foreignId('voucher_type_id')->constrained()->onDelete('restrict');
            $table->string('voucher_number');
            $table->date('date');
            $table->text('narration')->nullable();
            $table->decimal('amount', 15, 2);
            $table->foreignId('currency_id')->constrained()->onDelete('restrict');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vouchers');
    }
};
