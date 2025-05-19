<?php

use App\Models\Allocation;
use App\Models\Currency;
use App\Models\Route;
use App\Models\User;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAllocationCostsTable extends Migration
{
    public function up()
    {
        Schema::create('allocation_costs', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->foreignIdFor(Allocation::class, 'allocation_id')->onDelete('cascade');
            $table->string('name');
            $table->double('amount');
            $table->string('account_code');
            $table->foreignIdFor(User::class, 'created_by')->onDelete('cascade');
            $table->foreignIdFor(Route::class, 'route_id')->onDelete('cascade');
            $table->foreignIdFor(Currency::class, 'currency_id')->onDelete('cascade');
            $table->double('rate');
            $table->double('real_amount');
            $table->integer('status')->default(0);
            $table->timestamps();
            $table->double('quantity')->nullable();
            $table->boolean('vat')->default(0);
            $table->boolean('editable')->default(0);
            $table->string('type')->default('All');
            $table->integer('advancable')->default(0);
            $table->double('paid_amount')->default(0);
            $table->double('remaining_amount')->default(0);
            $table->integer('payment_status')->default(0);
            $table->boolean('return')->default(0);
            $table->text('reason')->nullable();
            $table->integer('missing_status')->default(0);
            // $table->index(['allocation_id', 'created_by', 'route_id', 'currency_id']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('allocation_costs');
    }
}
