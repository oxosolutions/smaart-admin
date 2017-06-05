<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGoalsResourceTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
         Schema::create('goals_resources', function(Blueprint $table){

            $table->increments('id');
            $table->string('resource_id')->nullable();
            $table->string('resource_title');
            $table->string('resource_image')->nullable();
            $table->text('resource_desc')->nullable();
            $table->unsignedInteger('created_by')->index();
            $table->softDeletes();
            $table->timestamps();
            $table->foreign('created_by')->references('id')->on('users')->onUpdate('cascade');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {

           Schema::drop('goals_resources');
        //
    }
}
