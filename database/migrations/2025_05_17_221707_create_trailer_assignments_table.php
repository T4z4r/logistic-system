<?php

use App\Models\Trailer;
use App\Models\Truck;
use App\Models\User;
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
        Schema::create('trailer_assignments', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->foreignIdFor(Trailer::class, 'trailer_id')->onDelete('cascade');
            $table->foreignIdFor(Truck::class, 'truck_id')->onDelete('cascade');
            $table->foreignIdFor(User::class, 'assigned_by')->onDelete('restrict');
            $table->boolean('status')->default(0);
            $table->timestamps();
            $table->index(['trailer_id', 'truck_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('trailer_assignments');
    }
};
