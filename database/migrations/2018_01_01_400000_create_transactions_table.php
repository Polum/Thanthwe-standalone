<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTransactionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->increments('id');
            $table->string('transaction_type');
            $table->string('transaction_source')->default('');
            $table->double('transaction_amount');
            $table->date('transaction_date');
            $table->integer('account_id')->unsigned();
            $table->string('reverse')->nullable();
            $table->timestamps();
            $table->softDeletes();

        });

        Schema::table('transactions', function($table){
            $table->foreign('account_id')->references('id')->on('accounts');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('transactions');
    }
}
