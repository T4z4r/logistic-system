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
      Schema::create('trailers', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('plate_number')->unique();
            $table->date('purchase_date');
            $table->double('amount')->nullable();
            $table->double('capacity');
            $table->string('manufacturer');
            $table->double('length');
            $table->string('trailer_type');
            $table->boolean('status')->default(0);
            $table->foreignId(column: 'added_by')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('trailers');
    }
};
