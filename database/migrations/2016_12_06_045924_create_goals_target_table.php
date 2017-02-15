<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGoalsTargetTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
         Schema::create('goals_targets', function(Blueprint $table){

            $table->increments('id');
            $table->string('target_id')->nullable();
            $table->string('target_title');
            $table->string('target_image')->nullable();
            $table->text('target_desc')->nullable();
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

           Schema::drop('goals_targets');
        //
    }
}
