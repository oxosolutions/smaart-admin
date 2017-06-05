<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGoalsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('goals', function(Blueprint $table){

            $table->increments('id');
            $table->string('goal_number');
            $table->string('goal_title');
            $table->string('goal_tagline');
            $table->text('goal_description');
            $table->string('goal_url');
            $table->string('goal_icon');
            $table->string('goal_icon_url');
            $table->string('goal_color_hex')->nullable();
            $table->string('goal_color_rgb')->nullable();
            $table->string('goal_color_rgb_a')->nullable();
            $table->string('goal_opacity');
            $table->string('goal_nodal_ministry');
            //$table->string('goal_other_ministrie');
            //$table->string('goal_schemes');
            //$table->string('goal_interventions');
            $table->unsignedInteger('created_by')->index();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('goals');
    }
}
