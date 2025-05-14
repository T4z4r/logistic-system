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
        Schema::create('customers', function (Blueprint $table) {
            $table->id();
            $table->string('contact_person');
            $table->string('TIN');
            $table->string('VRN');
            $table->string('phone');
            $table->string('address');
            $table->string('email');
            $table->string('company');
            $table->string('abbreviation');
            $table->foreignId('created_by')->constrained('users');
            $table->integer('status')->default(1);
            $table->integer('credit_term')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('customers');
    }
};
