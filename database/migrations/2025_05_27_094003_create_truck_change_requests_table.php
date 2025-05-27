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
        Schema::create('truck_change_requests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('allocation_id')->constrained('truck_allocations')->onDelete('cascade');
            $table->text('reason');
            $table->foreignId('requested_by')->constrained('users')->onDelete('cascade');
            $table->integer('status');
            $table->integer('approval_status')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('truck_change_requests');
    }
};
