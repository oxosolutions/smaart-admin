<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGoalsSchemaMappingTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
       Schema::create('goals_schemas_mappings', function(Blueprint $table){

            $table->increments('id');
            $table->unsignedInteger('goal_id')->index();
            $table->string('goal_type');
            $table->unsignedInteger('schemas_id');
            $table->timestamps();
            $table->softDeletes();
            $table->foreign('goal_id')->references('id')->on('goals')->onUpdate('cascade');
            $table->foreign('schemas_id')->references('id')->on('goals_schemas')->onUpdate('cascade');
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
        Schema::Drop('goals_schemas_mappings');
    }
}
