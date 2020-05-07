<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateHomecellActivitiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('homecell_activities', function (Blueprint $table) {
            $table->increments('id');
            $table->string('activity_name')->default('');
            $table->text('activity_description');
            $table->date('activity_set_date');
            $table->time('activity_start_time');
            $table->time('activity_end_time');
            $table->integer('homecell_id')->unsigned();
            $table->timestamps();
            $table->softDeletes();

        });

        Schema::table('homecell_activities', function($table){
            $table->foreign('homecell_id')->references('id')->on('homecells');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('homecell_activities');
    }
}
