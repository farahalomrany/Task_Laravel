<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrderDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('order_details', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('total_price')->nullable();
            $table->integer('count')->nullable();
            $table->boolean('is_fires')->nullable();
            $table->boolean('status')->nullable();
            $table->string('price')->nullable();
            $table->string('notes')->nullable();
            $table->string('cost')->nullable();
            $table->string('delay')->nullable();
         
           $table->bigInteger('order_id')->unsigned()->nullable();
            $table->foreign('order_id')->references('id')
            ->on('orders')->onDelete('cascade');
            $table->bigInteger('item_id')->unsigned()->nullable();
            $table->foreign('item_id')->references('id')
            ->on('items')->onDelete('cascade');
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
        Schema::dropIfExists('order_details');
    }
}
