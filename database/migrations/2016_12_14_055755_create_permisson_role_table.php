<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePermissonRoleTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::create('permisson_roles', function(Blueprint $table){
        $table->increments('id');
        $table->unsignedInteger('role_id');
        $table->unsignedInteger('permisson_id');
        $table->softDeletes();
        $table->timestamps();
        $table->foreign('role_id')->references('id')->on('roles')->onUpdate('cascade');
        $table->foreign('permisson_id')->references('id')->on('permissons')->onUpdate('cascade');
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

        Schema::drop('permisson_roles');
    }
}
