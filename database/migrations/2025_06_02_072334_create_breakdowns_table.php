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
      Schema::create('breakdowns', function (Blueprint $table) {
    $table->id();
    $table->foreignId('truck_id')->nullable()->constrained();
    $table->foreignId('trip_id')->nullable()->constrained();
    $table->foreignId('breakdown_category_id')->nullable()->constrained();
    $table->foreignId('breakdown_item_id')->nullable()->constrained();
    $table->foreignId('added_by_id')->nullable()->constrained('users');
    $table->text('description')->nullable();
    $table->string('cost')->nullable();
    $table->string('type_of_breakdown')->nullable();
    $table->integer('status')->default(0);
    $table->dateTime('brakedown_date')->nullable();
    $table->integer('state')->default(0);
    $table->foreignId('currency_id');
    $table->double('new_cost')->nullable();
    $table->boolean('is_paid')->default(0);
    $table->integer('pay_type')->default(0);
    $table->dateTime('payment_date')->nullable();
    $table->double('rate')->nullable();
    $table->double('real_amount')->nullable();
    $table->foreignId('new_cost_currency_id')->nullable()->constrained('currencies');
    $table->integer('breakdown_level')->default(1);
    $table->integer('payment_level')->default(1);
    $table->integer('code')->nullable();
    $table->string('location')->nullable();
    $table->string('breakdown_type')->nullable();
    $table->dateTime('closed_date')->nullable();
    $table->foreignId('closed_by_id')->nullable()->constrained('users');
    $table->integer('created_by')->default(1);
    $table->boolean('workshop_status')->default(0);
    $table->timestamps();
    $table->softDeletes();
});

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('breakdowns');
    }
};
