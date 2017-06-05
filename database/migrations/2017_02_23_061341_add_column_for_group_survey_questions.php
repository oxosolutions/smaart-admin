<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnForGroupSurveyQuestions extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('surrvey_questions', function(Blueprint $table){
            $table->unsignedInteger('group_id');

        });
    }

    /**      $table->foreign('survey_id')->references('id')->on('surrveys')->onUpdate('cascade');

     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
      Schema::table('surrvey_questions', function(Blueprint $table){
            $table->drop('group_id');

        });
    }
}
