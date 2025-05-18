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
       Schema::create('route_costs', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name');
            $table->double('amount')->default(0);
            $table->string('account_code')->nullable();
            $table->foreignId('created_by')->constrained('users')->onDelete('restrict');
            $table->foreignId('cost_id')->constrained('common_costs')->onDelete('cascade'); // Assuming cost_id references common_costs
            $table->foreignId('route_id')->nullable()->constrained('routes')->onDelete('cascade');
            $table->foreignId('currency_id')->constrained('currencies')->onDelete('restrict');
            $table->double('rate')->default(0);
            $table->double('real_amount')->default(0);
            $table->timestamps();
            $table->double('quantity')->nullable();
            $table->boolean('vat')->default(0);
            $table->boolean('editable')->default(0);
            $table->string('type')->default('All');
            $table->integer('advancable')->default(0);
            $table->boolean('return')->default(0);
            $table->integer('status')->default(0);
            $table->index(['route_id', 'cost_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('route_costs');
    }
};
