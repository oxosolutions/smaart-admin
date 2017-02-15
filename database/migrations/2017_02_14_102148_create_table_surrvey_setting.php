<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableSurrveySetting extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('surrvey_settings',function(Blueprint $table){
            $table->increments('id');
            $table->unsignedInteger('surrvey_id');
            $table->string('type');
            $table->string('key');
            $table->string('value');
            $table->timestamps();
            $table->softDeletes();
            $table->foreign('surrvey_id')->references('id')->on('surrveys')->onUpdate('cascade');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('surrvey_settings');
    }
}
