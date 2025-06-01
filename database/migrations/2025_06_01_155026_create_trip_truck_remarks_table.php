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
            Schema::create('trip_truck_remarks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('truck_id')->constrained()->onDelete('cascade');
            $table->text('remark')->nullable();
            $table->integer('status')->default(1);
            $table->string('remarked_by');
            $table->foreignId('created_by')->constrained('users')->onDelete('cascade');
            $table->timestamps();
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('trip_truck_remarks');
    }
};
