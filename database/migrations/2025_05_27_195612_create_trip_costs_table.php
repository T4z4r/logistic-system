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
        Schema::create('trip_costs', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->double('amount');
            $table->unsignedBigInteger('trip_id');
            $table->unsignedBigInteger('cost_id');
            $table->tinyInteger('status');
            $table->unsignedBigInteger('created_by');
            $table->timestamps();
            $table->tinyInteger('return')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('trip_costs');
    }
};
