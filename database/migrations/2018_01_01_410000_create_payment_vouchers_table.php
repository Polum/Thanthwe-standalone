<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePaymentVouchersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('payment_vouchers', function (Blueprint $table) {
            $table->increments('id');
            $table->string('requested_by');
            $table->string('payee');
            $table->text('expenditure_details');
            $table->double('amount');
            $table->date('expenditure_date');
            $table->integer('transaction_id')->unsigned();
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::table('payment_vouchers', function($table){
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
        Schema::dropIfExists('payment_vouchers');
    }
}
