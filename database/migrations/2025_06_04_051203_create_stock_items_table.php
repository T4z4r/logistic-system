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
        Schema::create('stock_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->constrained()->onDelete('cascade');
            $table->foreignId('stock_group_id')->constrained()->onDelete('restrict');
            $table->foreignId('unit_id')->constrained()->onDelete('restrict');
            $table->string('name');
            $table->decimal('opening_stock', 15, 2)->default(0);
            $table->decimal('rate', 15, 2)->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stock_items');
    }
};
