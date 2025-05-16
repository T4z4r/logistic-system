<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTrucksTable extends Migration
{
    public function up()
    {
        Schema::create('trucks', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->date('purchase_date');
            $table->string('plate_number')->unique();
            $table->string('body_type');
            $table->string('truck_type');
            $table->string('fuel_type');
            $table->string('fuel_capacity');
            $table->string('trailer_connection');
            $table->integer('trailer_capacity');
            $table->string('transmission'); // Corrected from transimission
            $table->string('mileage');
            $table->string('vehicle_model');
            $table->string('manufacturer');
            $table->string('year')->nullable();
            $table->string('color')->nullable();
            $table->string('engine_number')->nullable();
            $table->string('engine_capacity')->nullable();
            $table->double('gross_weight')->nullable();
            $table->string('location')->nullable();
            $table->integer('status')->default(0);

            $table->double('amount')->nullable();
            $table->double('capacity')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('trucks');
    }
}
