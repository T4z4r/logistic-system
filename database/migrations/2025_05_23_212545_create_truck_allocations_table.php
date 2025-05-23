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
        Schema::create('truck_allocations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('allocation_id');
            $table->foreignId('truck_id');
            $table->foreignId('trailer_id');
            $table->foreignId('driver_id');
            $table->tinyInteger('status')->default(0);
            $table->double('total_cost')->default(0);
            $table->double('additional_cost')->default(0);
            $table->double('income')->default(0);
            $table->double('loaded')->default(0);
            $table->double('offloaded')->default(0);
            $table->date('loading_date')->nullable();
            $table->date('offload_date')->nullable();
            $table->foreignId('currency')->default(0);
            $table->integer('cost_status')->default(0);
            $table->integer('payment_status')->default(0);
            $table->foreignId('created_by');
            $table->timestamps();
            $table->string('pod', 250)->nullable();
            $table->integer('rescue_status')->default(0);
            $table->double('planned')->default(0);
            $table->tinyInteger('initiation_status')->default(0);
            $table->tinyInteger('trip_status')->default(0);
            $table->double('usd_income')->default(0);
            $table->integer('mobilization')->default(0);
            $table->integer('mobilization_route')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('truck_allocations');
    }
};
