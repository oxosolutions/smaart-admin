<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGoalsIntervationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('goals_interventions', function(Blueprint $table){

            $table->increments('id');
            $table->string('intervent_id')->nullable();
            $table->string('intervent_title');
            $table->string('intervent_image')->nullable();
            $table->text('intervent_desc')->nullable();
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
       Schema::drop('goals_interventions');
    }
}
