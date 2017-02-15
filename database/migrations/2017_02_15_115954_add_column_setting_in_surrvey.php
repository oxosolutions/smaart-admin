<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnSettingInSurrvey extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('surrveys',function(Blueprint $table){
            $table->text('error_message_value')->nullable();
            $table->text('authorize')->nullable();
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
            $table->drop('error_message_value');
            $table->drop('authorize');
        });
    }
}
