<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('items', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('title')->nullable();
            $table->string('description')->nullable();
            $table->boolean('is_avaliable')->nullable();
            $table->integer('in_orders')->nullable();
            $table->string('order')->nullable();
            $table->integer('menu_order')->nullable();
            $table->integer('menu_cat_id')->nullable();
            $table->integer('monthly_avg')->nullable();
            $table->integer('rate_star')->nullable();
            $table->integer('sell_price')->nullable();
    
           $table->bigInteger('category_id')->unsigned()->nullable();
            $table->foreign('category_id')->references('id')
            ->on('categories')->onDelete('cascade');
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
        Schema::dropIfExists('items');
    }
}
