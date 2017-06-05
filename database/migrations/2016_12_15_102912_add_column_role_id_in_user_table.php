<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnRoleIdInUserTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        
    Schema::table('users',function($table) {
        $table->integer('role_id')->unsigned()->index()->default(1);
        $table->foreign('role_id')->references('id')->on('roles')->onUpdate('cascade');
    });



    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        
      Schema::table('users',function($table){
								$table->dropForeign('role_id');
						});
    }
}
