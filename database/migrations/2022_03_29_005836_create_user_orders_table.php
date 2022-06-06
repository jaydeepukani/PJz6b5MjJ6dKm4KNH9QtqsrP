<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_orders', function (Blueprint $table) {
            $table->id();
            $table->string('payment_id');
            $table->string('user_id');
            $table->string('plan_id');
            $table->tinyInteger('payment_status')->nullable();
            $table->json('payment_details')->nullable();
            $table->tinyInteger('status');
            $table->string('response_message')->nullable();
            $table->dateTime('purchased_at')->nullable();
            $table->dateTime('expired_at')->nullable();
            $table->tinyInteger('payment_gateway_using');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('user_orders');
    }
}
