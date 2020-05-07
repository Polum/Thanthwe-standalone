<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOfferingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('offerings', function (Blueprint $table) {
            $table->increments('id');
            $table->string('other_type')->nullable();
            $table->double('offering_amount')->default(0);
            $table->date('offering_date')->nullable();
            $table->integer('member_id')->unsigned()->nullable();
            $table->integer('offering_type_id')->unsigned()->nullable();
            $table->integer('transaction_id')->unsigned()->nullable();
            $table->timestamps();
            $table->softDeletes();

        });

        Schema::table('offerings', function($table){
            $table->foreign('member_id')->references('id')->on('members');
            $table->foreign('offering_type_id')->references('id')->on('offering_types');
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
        Schema::dropIfExists('offerings');
    }
}
