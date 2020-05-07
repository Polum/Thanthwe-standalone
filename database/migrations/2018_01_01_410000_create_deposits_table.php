<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDepositsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('deposits', function (Blueprint $table) {
            $table->increments('id');
            $table->string('deposit_type');
            $table->string('deposit_source');
            $table->double('deposit_amount');
            $table->text('deposit_details');
            $table->date('deposit_date');
            $table->integer('transaction_id')->unsigned();
            $table->timestamps();
            $table->softDeletes();

        });

        Schema::table('deposits', function($table){
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
        Schema::dropIfExists('deposits');
    }
}
