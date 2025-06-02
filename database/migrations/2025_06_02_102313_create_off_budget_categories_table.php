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
        Schema::create('off_budget_categories', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->bigInteger('created_by')->nullable();
            $table->bigInteger('cost_id')->nullable();
            $table->timestamps();

            // $table->foreign('created_by')->references('id')->on('users')->onDelete('cascade');
            // $table->foreign('cost_id')->references('id')->on('truck_costs')->onDelete('cascade');
        });
    }


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('off_budget_categories');
    }
};
