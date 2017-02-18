<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnInSurrveyTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
  Schema::table('surrveys',function(Blueprint $table){
            $table->string('surrvey_table')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
   Schema::table('surrveys',function(Blueprint $table){
            $table->drop('surrvey_table')->nullable();
        });
    }
}
