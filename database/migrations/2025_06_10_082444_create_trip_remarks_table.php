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
        Schema::create('trip_remarks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('trip_id');
            $table->text('remark')->nullable();
            $table->string('remarked_by');
            $table->foreignId('status')->default(0);
            $table->foreignId('created_by');
            $table->timestamps();

            //FOREIGN KEY CONSTRAINTS
            $table->foreign('trip_id')->references('id')->on('trips')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('trip_remarks');
    }
};
