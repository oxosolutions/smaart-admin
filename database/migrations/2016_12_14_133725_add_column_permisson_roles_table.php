<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnPermissonRolesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('permisson_roles', function($table){

            $table->boolean('read')->after('permisson_id');
            $table->boolean('write')->after('read');
            $table->boolean('delete')->after('write');
                
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
        Schema::table('permisson_roles', function($table){

            $table->drop('read');
            $table->drop('write');
             $table->drop('delete');
        });
    }
}
