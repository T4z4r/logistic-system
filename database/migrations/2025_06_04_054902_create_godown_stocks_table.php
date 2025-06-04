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
             Schema::create('godown_stocks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->constrained()->onDelete('cascade');
            $table->foreignId('stock_item_id')->constrained()->onDelete('cascade');
            $table->foreignId('godown_id')->constrained()->onDelete('restrict');
            $table->decimal('quantity', 15, 2);
            $table->timestamps();
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('godown_stocks');
    }
};
