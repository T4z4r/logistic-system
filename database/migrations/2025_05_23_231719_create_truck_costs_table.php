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
       Schema::create('truck_costs', function (Blueprint $table) {
    $table->id();
    $table->bigInteger('allocation_id');
    $table->foreignId('truck_id')->constrained()->onDelete('cascade');
    $table->string('name');
    $table->double('amount');
    $table->string('account_code');
    $table->foreignId('route_id')->nullable()->constrained()->onDelete('set null');
    $table->foreignId('currency_id')->constrained()->onDelete('cascade');
    $table->double('rate');
    $table->double('real_amount');
    $table->integer('status')->default(0);
    $table->foreignId('created_by');
    $table->double('quantity')->nullable();
    $table->boolean('editable')->default(0);
    $table->string('type')->default('All');
    $table->integer('mobilization')->default(0);
    $table->integer('advancable')->default(0);
    $table->boolean('return')->default(0);
    $table->double('transaction_charges')->nullable();
    $table->double('service_charges')->nullable();
    $table->double('vat_charges')->nullable();
    $table->double('paid_amount')->nullable();
    $table->double('real_paid_amount')->nullable();
    $table->integer('payment_status')->default(1);
    $table->text('reason')->nullable();
    $table->integer('missing_status')->nullable();
    $table->timestamps();
});

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('truck_costs');
    }
};
