<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('allocations', function (Blueprint $table) {
            $table->dropColumn('cargo_nature_id');
        });
    }

    public function down(): void
    {
        Schema::table('allocations', function (Blueprint $table) {
            $table->unsignedBigInteger('cargo_nature_id')->nullable(); // Adjust nullable or default as needed
        });
    }
};
