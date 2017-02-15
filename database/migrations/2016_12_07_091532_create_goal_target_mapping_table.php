<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGoalTargetMappingTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('goals_target_mappings', function(Blueprint $table){

            $table->increments('id');
            $table->unsignedInteger('goal_id')->index();
            $table->string('goal_type');
            $table->unsignedInteger('targets_id');
            $table->timestamps();
            $table->softDeletes();
            $table->foreign('goal_id')->references('id')->on('goals')->onUpdate('cascade');
            $table->foreign('targets_id')->references('id')->on('goals_targets')->onUpdate('cascade');
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
    }
}
