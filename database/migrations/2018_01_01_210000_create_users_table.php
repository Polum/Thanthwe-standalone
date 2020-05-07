<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('email')->unique();
            $table->string('password');
            $table->string('phone')->nullable();
            $table->text('address')->nullable();
            $table->boolean('employee')->default(false);
            $table->string('occupation')->default('')->nullable();
            $table->integer('role_id')->unsigned()->nullable();
            $table->integer('homecell_id')->unsigned()->nullable();
            $table->rememberToken();
            $table->timestamps();
            $table->softDeletes();

        });

        Schema::table('users', function($table){
            $table->foreign('role_id')->references('id')->on('roles');
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
        Schema::dropIfExists('users');
    }
}
