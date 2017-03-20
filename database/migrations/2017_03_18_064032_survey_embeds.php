<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class SurveyEmbeds extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('survey_embeds', function(Blueprint $table){
                $table->increments('id');
                $table->unsignedInteger('survey_id')->index();
                $table->unsignedInteger('user_id')->index();
                $table->unsignedInteger('org_id')->index();
                $table->text('embed_token');
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
        Schema::drop('survey_embeds');
    }
}
