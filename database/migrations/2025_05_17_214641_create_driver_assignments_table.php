<?php

use App\Models\User;
use App\Models\Truck;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('driver_assignments', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->foreignIdFor(User::class, 'driver_id')->onDelete('set null');
            $table->foreignIdFor(Truck::class, 'truck_id')->onDelete('set null');
            $table->foreignIdFor(User::class, 'assigned_by')->onDelete('set null');
            $table->boolean('status')->default(0);
            $table->timestamps();
            $table->index(['driver_id', 'truck_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('driver_assignments');
    }
};
