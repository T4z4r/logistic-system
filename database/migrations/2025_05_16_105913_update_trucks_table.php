<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateTrucksTable extends Migration
{
    public function up()
    {
        Schema::table('trucks', function (Blueprint $table) {


            // Add added_by column if it doesn't exist
            if (!Schema::hasColumn('trucks', 'added_by')) {
                $table->unsignedBigInteger('added_by')->nullable();
                $table->foreign('added_by')->references('id')->on('users')->onDelete('cascade');
            }
        });
    }

    public function down()
    {
        Schema::table('trucks', function (Blueprint $table) {
            // Drop foreign key and added_by column if exists
            if (Schema::hasColumn('trucks', 'added_by')) {
                $table->dropForeign(['added_by']);
                $table->dropColumn('added_by');
            }


        });
    }
}
