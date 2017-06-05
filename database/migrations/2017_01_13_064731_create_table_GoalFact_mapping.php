<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableGoalFactMapping extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::create('goal_fact_mappings',function(Blueprint $table)
        {
            $table->increments('id');
            $table->unsignedInteger('goal_id')->index;
            $table->string('goal_type');
            $table->unsignedInteger('fact_id')->index;
            $table->softDeletes();
            $table->timestamps();
            $table->foreign('goal_id')->references('id')->on('goals')->onUpdate('cascade');
            $table->foreign('fact_id')->references('id')->on('goal_facts')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
        Schema::drop('goal_fact_mappings');
    }
}
