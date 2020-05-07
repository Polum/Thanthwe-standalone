<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateHomecellsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('homecells', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('zone_id')->unsigned();
            $table->string('name')->default('');
            $table->string('location')->default('');
            $table->timestamps();
            $table->softDeletes();

        });

        Schema::table('homecells', function($table){
            $table->foreign('zone_id')->references('id')->on('zones');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('homecells');
    }
}
