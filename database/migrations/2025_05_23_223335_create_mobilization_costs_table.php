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
      Schema::create('mobilization_costs', function (Blueprint $table) {
    $table->id();
    $table->string('name');
    $table->double('amount');
    $table->double('quantity')->nullable();
    $table->string('account_code');
    $table->unsignedBigInteger('created_by');
    $table->unsignedBigInteger('cost_id');
    $table->unsignedBigInteger('route_id')->nullable();
    $table->unsignedBigInteger('currency_id');
    $table->double('rate');
    $table->double('real_amount');
    $table->boolean('vat')->default(0);
    $table->boolean('editable')->default(0);
    $table->string('type')->default('All');
    $table->timestamps();
    $table->integer('advancable')->default(0);
    $table->boolean('return')->default(0);
    $table->integer('status')->default(0);
});

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mobilization_costs');
    }
};
