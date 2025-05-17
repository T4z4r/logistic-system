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
      Schema::create('common_costs', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name')->unique();
            $table->bigInteger('ledger_id');
            $table->bigInteger('created_by');
            $table->timestamps();
            $table->boolean('vat')->default(0);
            $table->boolean('editable')->default(0);
            $table->integer('advancable')->default(0);
            $table->boolean('return')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('common_costs');
    }
};
