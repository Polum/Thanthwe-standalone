<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCommitteesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('committees', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('committee_type_id')->unsigned()->nullable();
            $table->integer('member_id')->unsigned()->nullable();
            $table->integer('department_id')->unsigned()->nullable();
            $table->integer('ministry_id')->unsigned()->nullable();
            $table->timestamps();
        });

        Schema::table('committees', function($table){
            $table->foreign('committee_type_id')->references('id')->on('committee_types');
            $table->foreign('member_id')->references('id')->on('members');
            $table->foreign('department_id')->references('id')->on('departments');
            $table->foreign('ministry_id')->references('id')->on('ministries');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('committees');
    }
}
