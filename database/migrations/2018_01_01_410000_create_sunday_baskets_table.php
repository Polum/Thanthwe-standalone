<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSundayBasketsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sunday_baskets', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('service_no');
            $table->double('total_offerings');
            $table->date('offering_date');
            $table->integer('transaction_id')->unsigned();
            $table->timestamps();
            $table->softDeletes();

        });

        Schema::table('sunday_baskets', function($table){
            $table->foreign('transaction_id')->references('id')->on('transactions');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('sunday_baskets');
    }
}
