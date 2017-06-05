<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateQuestion extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('surrvey_questions', function(Blueprint $table)
        {
            $table->increments('id');
            $table->unsignedInteger('surrvey_id')->index();
            $table->text('answer');
            $table->string('question');
            $table->foreign('surrvey_id')->references('id')->on('surrveys')->onUpdate('cascade');
            $table->timestamps();
            $table->softDeletes();

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('surrvey_questions');
        //
    }
}
