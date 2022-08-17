<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->date('order_date')->nullable();
            $table->string('total_price')->nullable();
            $table->boolean('payment_state')->nullable();
            $table->enum('payment_method', ['cash', 'visa'])->nullable();
            $table->boolean('status')->nullable();
            $table->integer('print_count')->nullable();
            $table->string('total_cost')->nullable();
            $table->string('total_after_taxes')->nullable();
            $table->string('discount_amount')->nullable();
            $table->string('taxes')->nullable();
            $table->string('consuption_taxes')->nullable();
            $table->string('local_adminstration')->nullable();
            $table->string('rebuild_taxes')->nullable();
            $table->string('notes')->nullable();
            $table->string('client_name')->nullable();
            
           

           $table->bigInteger('table_id')->unsigned()->nullable();
            $table->foreign('table_id')->references('id')
            ->on('tables')->onDelete('cascade');
            $table->bigInteger('user_id')->unsigned()->nullable();
            $table->foreign('user_id')->references('id')
            ->on('users')->onDelete('cascade');
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
        Schema::dropIfExists('orders');
    }
}
