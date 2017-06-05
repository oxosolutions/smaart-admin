<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnInSurrveyForSetting extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::table('surrveys', function(Blueprint $table)
        {
            $table->enum('status',['enable', 'disable']);
            $table->enum('scheduling',['enable', 'disable']);
            $table->dateTime('start_date')->nullable();
            $table->dateTime('expire_date')->nullable();
            $table->enum('timer_status',['enable', 'disable']);
            $table->enum('timer_type',['expire_time', 'durnation']);
            $table->time('timer_durnation')->nullable();
            $table->enum('response_limit_status',['enable', 'disable']);
            $table->integer('response_limit')->nullable();
            $table->enum('response_limit_type',['per_user', 'per_ip_address']); 
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('surrveys',function(Blueprint $table)
        {
             $table->drop('status');
             $table->drop('scheduling');
             $table->drop('start_date');
             $table->drop('expire_date');
             $table->drop('timer_status');
             $table->drop('timer_type');
             $table->drop('timer_durnation');
             $table->drop('response_limit_status');
             $table->drop('response_limit');
             $table->drop('response_limit_type');
        });        
    }
}
