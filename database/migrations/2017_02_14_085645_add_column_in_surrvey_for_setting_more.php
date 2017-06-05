<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnInSurrveyForSettingMore extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
         Schema::table('surrveys', function(Blueprint $table)
        {
            $table->enum('authentication_required',['enable', 'disable']);
            $table->enum('authentication_type',['role_based', 'individual_based']);
            $table->enum('error_messages',['enable', 'disable']);
           
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
         Schema::table('surrveys', function(Blueprint $table)
        {
            $table->drop('authentication_required');
            $table->drop('authentication_type');
            $table->drop('error_messages');
        });
         
    }
}
