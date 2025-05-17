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
        Schema::create('approval_levels', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('approval_id');
            $table->bigInteger('role_id');
            $table->string('level_name');
            $table->string('rank');
            $table->string('label_name');
            $table->boolean('status');
            $table->timestamps();
            $table->unique(['approval_id', 'level_name']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('approval_levels');
    }
};
