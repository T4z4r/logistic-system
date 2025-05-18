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
      Schema::create('allocations', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('ref_no')->nullable();
            $table->foreignId('Customer_id')->constrained('customers')->onDelete('restrict');
            $table->double('amount');
            $table->double('quantity');
            $table->string('cargo');
            $table->string('cargo_ref');
            $table->double('estimated_pay')->default(0);
            $table->unsignedBigInteger('cargo_nature_id');
            $table->foreign('cargo_nature_id')->references('id')->on('cargo_natures')->onDelete('restrict');
            $table->unsignedBigInteger('payment_mode_id');
            $table->foreign('payment_mode_id')->references('id')->on('payment_modes')->onDelete('restrict');
            $table->string('loading_site')->nullable();
            $table->string('offloading_site')->nullable();
            $table->string('clearance')->default('No');
            $table->string('container')->default('No');
            $table->string('container_type')->nullable();
            $table->string('dimensions')->nullable();
            $table->string('payment_currency');
            $table->double('rate')->nullable();
            $table->double('real_amount')->nullable();
            $table->foreignId('route_id')->constrained('routes')->onDelete('restrict');
            $table->date('start_date');
            $table->date('end_date');
            $table->string('unit')->default('Ton');
            $table->integer('status')->default(0);
            $table->integer('approval_status')->default(0);
            $table->integer('type')->default(1);
            $table->string('state')->nullable();
            $table->foreignId('created_by')->constrained('users')->onDelete('restrict');
            $table->timestamps();
            $table->integer('goingload_id')->nullable();
            $table->integer('approver_id')->default(0);
            $table->integer('disapprover_id')->default(0);
            $table->double('usd_income')->default(0);
            $table->index(['Customer_id', 'route_id', 'created_by', 'cargo_nature_id', 'payment_mode_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('allocations');
    }
};
