<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTripsTable extends Migration
{
    public function up()
    {
        Schema::create('trips', function (Blueprint $table) {
            $table->id();
            $table->string('ref_no');
            $table->unsignedBigInteger('allocation_id');
            $table->date('initiated_date');
            $table->double('total_payment');
            $table->boolean('status')->default(true);
            $table->string('state');
            $table->unsignedBigInteger('created_by');
            $table->integer('type')->default(1);
            $table->timestamps();
            $table->integer('approval_status')->default(0);
            $table->integer('completion_approval_status')->default(0);
            $table->double('invoiced_income')->default(0);
        });
    }

    public function down()
    {
        Schema::dropIfExists('trips');
    }
}