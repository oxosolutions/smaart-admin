<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGoalInterventionMappingTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
       Schema::create('goals_interventions_mappings', function(Blueprint $table){

            $table->increments('id');
            $table->unsignedInteger('goal_id')->index();
            $table->string('goal_type');
            $table->unsignedInteger('interventions_id');
            $table->timestamps();
            $table->softDeletes();
            $table->foreign('goal_id')->references('id')->on('goals')->onUpdate('cascade');
            $table->foreign('interventions_id')->references('id')->on('goals_interventions')->onUpdate('cascade');
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
        Schema::drop('goals_interventions_mappings');
    }
}
