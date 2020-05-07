<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMembersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('members', function (Blueprint $table) {
            $table->increments('id');
            $table->string('first_name')->nullable();
            $table->string('last_name')->nullable();
            $table->string('sex')->nullable();
            $table->date('dob')->nullable();
            $table->string('marital_status')->nullable();
            $table->text('address')->nullable();
            $table->string('phone')->nullable();
            $table->string('email')->unique()->nullable();
            $table->string('member_type')->nullable();
            $table->string('occupation')->nullable();
            $table->integer('envelop_no')->nullable();
            $table->string('previous_church')->nullable();
            $table->string('joining_means')->nullable();
            $table->date('join_date')->nullable();
            $table->integer('homecell_id')->unsigned()->nullable();
            $table->integer('family_id')->unsigned()->nullable();
            $table->date('left')->nullable();
            $table->date('deceased')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::table('members', function($table){
            $table->foreign('homecell_id')->references('id')->on('homecells');
            $table->foreign('family_id')->references('id')->on('families');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('members');
    }
}
