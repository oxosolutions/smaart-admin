<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGoalsMinistryMappingTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('goals_ministry_mappings', function(Blueprint $table){

        	$table->increments('id');
        	$table->unsignedInteger('goal_id')->index();
        	$table->string('goal_type');
        	$table->unsignedInteger('ministry_id');
        	$table->timestamps();
        	$table->softDeletes();
        	$table->foreign('goal_id')->references('id')->on('goals')->onUpdate('cascade');
        	$table->foreign('ministry_id')->references('id')->on('ministries')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('goals_ministry_mappings');
    }
}
