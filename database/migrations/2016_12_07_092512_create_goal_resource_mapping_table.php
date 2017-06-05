<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGoalResourceMappingTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
      Schema::create('goals_resources_mappings', function(Blueprint $table){

            $table->increments('id');
            $table->unsignedInteger('goal_id')->index();
            $table->string('goal_type');
            $table->unsignedInteger('resources_id');
            $table->timestamps();
            $table->softDeletes();
            $table->foreign('goal_id')->references('id')->on('goals')->onUpdate('cascade');
            $table->foreign('resources_id')->references('id')->on('goals_resources')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
          Schema::drop('goals_resources_mappings');
    }
}
