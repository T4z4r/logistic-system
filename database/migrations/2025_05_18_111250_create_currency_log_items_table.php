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
        Schema::create('currency_log_items', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->foreignId('currency_log_id')->constrained('currency_logs')->onDelete('cascade');
            $table->foreignId('currency_id')->constrained('currencies')->onDelete('restrict');
            $table->double('rate');
            $table->foreignId('created_by')->constrained('users')->onDelete('restrict');
            $table->double('corridor_rate')->default(1);
            $table->timestamps();
            $table->index(['currency_log_id', 'currency_id', 'created_by']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('currency_log_items');
    }
};
